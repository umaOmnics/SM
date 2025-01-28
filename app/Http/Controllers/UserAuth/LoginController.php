<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UsersController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use Lcobucci\JWT\Parser as JWTParser;
use Nette\Schema\ValidationException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Exception;
use Illuminate\Validation\ValidationException as AnotherValidationException;
use Laravel\Passport\HasApiTokens;

class LoginController extends Controller
{
    use HandlesOAuthErrors,HasApiTokens;
    /**
     * The authorization server.
     *
     * @var AuthorizationServer
     */
    protected $server;
    /**
     * The token repository instance.
     *
     * @var TokenRepository
     */
    protected $tokens;
    /**
     * The JWT parser instance
     *
     * @var JWTParser
     */
    protected $jwt;
    /**
     * Create a new controller instance.
     *
     * @param AuthorizationServer $server
     * @param TokenRepository $tokens
     * @param JwtParser $jwt
     * @return void
     */
    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt
    ) {
        $this->jwt = $jwt;
        $this->server = $server;
        $this->tokens = $tokens;
    }

    /**
     * Method to allow the user to log in with accessToken and refreshToken
     * @param ServerRequestInterface $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function login(ServerRequestInterface $request):JsonResponse
    {
        try {
            $login_parameters = $request->getParsedBody();
            $email = $login_parameters['username'];
            $password = $login_parameters['password'];
            $client_id = $login_parameters['client_id'];
            $this->authenticate($email, $password);
            $user = Auth::user();
//            dd($login_parameters, $user);
            if ($user){
                if ($user->sys_admin == true && $user->is_active == true) {
                    $tokens = $user->tokens()->where('client_id', $client_id)->pluck('id');
                    if (!empty($tokens)) {
                        foreach ($tokens as $token) {
                            $token_update = Token::where('id', $token)->update(['revoked' => true]);
                            if ($token_update) {
                                RefreshToken::where('access_token_id', $token)->update(['revoked' => true]);
                            }
                        }
                    }
//                    dd($request);
                    $tokenDetails = $this->issueToken($request);
                    $token_contents = json_decode((string)$tokenDetails->content(), true);
                    if ($user->two_factor_secret == null) {
                        $user_controller = new UsersController();
                        $user_details = $user_controller->userDetails($user->id,'login');
                        return response()->json([
                            'tokenDetails' => $token_contents,
                            'userDetails' => $user_details,
                            'status' => 'Success',
                            'message' => 'Welcome to Omnics Manager, Explore our new features.',
                        ], 200);
                    } else {
                        $user_details = [
                            'id' => $user->id,
                            'has2FA' => true,
                        ];
                        return response()->json([
                            'status' => 'Success',
                            'userDetails' => $user_details,
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'status' => 'Unauthorized',
                        'message' => 'You are not allowed to log into the system, Please contact administrator',
                    ], 401);
                }
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Error in logging into the system, Please try after some time',
                ],400);
            }
        } catch (ValidationException $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception,
            ],500);
        }
    }//End Function

    /**
     * Method allow to refresh the login user token details
     * @param ServerRequestInterface $request
     * @return JsonResponse
     * @throws OAuthServerException
     */
    public function issueNewTokenDetails(ServerRequestInterface $request):JsonResponse
    {
        $login_parameters = $request->getParsedBody();
        $client_id = $login_parameters['client_id'];
        $new_token_Details = $this->issueToken($request);
        $token_contents = json_decode((string)$new_token_Details->content(), true);
        if ($client_id == 2) {
                $user = Auth::user();
            if (!empty($user)) {
                $tokens = $user->tokens()->where('revoked', 0)->get();
                foreach ($tokens as $token) {
                    RefreshToken::where('access_token_id', $token->id)
                        ->update(['expires_at' => Carbon::now()->addDays(7)]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not authenticated.',
                ], 401);
            }
        }
        return response()->json([
            'token_type' => 'Bearer',
            'expires_in' => 900,
            'access_token' => $token_contents['access_token'],
            'refresh_token' => $token_contents['refresh_token'],
        ], 200);
    } // End Function

    /**
     * Authorize a client to access the user's account.
     * @param ServerRequestInterface $request
     * @throws OAuthServerException
     */
//    public function issueToken(ServerRequestInterface $request)
//    {
//        return $this->withErrorHandling(function () use ($request) {
//            return $this->convertResponse(
//                $this->server->respondToAccessTokenRequest($request, new Response())
//            );
//        });
//    }//End Function

    public function issueToken(ServerRequestInterface $request)
    {
        // Validate request for necessary fields
//        $data = $request->getParsedBody();
//
//        if (!isset($data['email']) || !isset($data['password'])) {
//            return $this->convertResponse(
//                response()->json(['error' => 'Missing credentials'], 400)
//            );
//        }

        return $this->withErrorHandling(function () use ($request) {
            // Ensure proper OAuth response handling
            return $this->convertResponse(
                $this->server->respondToAccessTokenRequest($request, new Response())
            );
        });
    }

    /**
     * Attempt to authenticate the request's credentials.
     * @return void
     * @throws AnotherValidationException
     */
    public function authenticate($email, $password)
    {
        $this->ensureIsNotRateLimited($email);
        if (! Auth::attempt(['email'=>$email, 'password'=>$password])) {
            RateLimiter::hit($this->throttleKey($email));
            throw AnotherValidationException::withMessages([
                'content' => __('auth.failed'),
            ]);
        }
        RateLimiter::clear($this->throttleKey($email));
    }//End Function

    /**
     * Attempt to authenticate the request's credentials got too many attempts or not.
     * @return void
     * @throws AnotherValidationException
     */
    public function ensureIsNotRateLimited($email)
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($email), 5)) {
            return;
        }
        $request = new Request();
        event(new Lockout($request));
        $seconds = RateLimiter::availableIn($this->throttleKey($email));
        throw AnotherValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }//End Function

    /**
     * Get the rate limiting throttle key for the request.
     * @param $email
     * @return string
     */
    public function throttleKey($email):string
    {
        return Str::lower($email);
    }//End Function

    /**
     * Method allow to get the details of the User who enabled two-factor authentication with tokens and details
     * @param  ServerRequestInterface  $request
     * @param  $id
     * @return JsonResponse
     * @throws Exception
     */
    public function loginAfter2FA(ServerRequestInterface $request, $id):JsonResponse
    {
        try {
            $tokenDetails = $this->issueToken($request);
            $token_contents = json_decode((string)$tokenDetails->content(), true);
            $user_controller = new UsersController();
            $user_details = $user_controller->userDetails($id,'login');
            return response()->json([
                'tokenDetails' => $token_contents,
                'userDetails' => $user_details,
                'status' => 'Success',
                'message' => 'Welcome to Omnics Manager, Explore our new features.',
            ], 200);
        } catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function
}
