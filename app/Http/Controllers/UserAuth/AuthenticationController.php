<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\UsersController;
use App\Models\Folders;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Nette\Schema\ValidationException;
use Illuminate\Validation\Rules;
use Exception;
class AuthenticationController extends Controller
{
    /**
     * Method to allow the new sys_user to Register
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(Request $request):JsonResponse
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'username' => 'required|string|max:255|unique:users',
            ]);
            $user = User::create([
                'salutations_id' => (isset($request->salutations_id)) ? $request->salutations_id : 1,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'sys_admin' => true,
            ]);
            if ($user){
                $user->roles()->attach($request->role_id);
                $user_controller = new UsersController();
                $user_details = $user_controller->userDetails($user->id);
                return response()->json([
                    'userDetails' => $user_details,
                    'status' => 'Success',
                    'message' => 'Registration is successful, Login to experience our features.',
                ], 200);
            } else{
                return response()->json([
                    'status' => 'Database - Error',
                    'message' => 'Problem in registering user, try again after some time',
                ], 205);
            }
        } catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function
    /**
     * Method to Destroy authentication session
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            $tokens =  $user->tokens->pluck('id');
            if (!empty($tokens)){
                Token::whereIn('id', $tokens)
                    ->update(['revoked' => true]);
                RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => false]);
                $accessToken = Auth::guard('api')->user()->tokens->each(function ($token, $key){
                    $token->delete();
                });
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Thank you and we are looking forward to see you again.',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'First login to the system',
                ], 200);
            }
        } catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function
}
