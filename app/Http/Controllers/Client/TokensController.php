<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;

class TokensController extends Controller
{
    /**
     * Method to retrieve the client tokens from the application.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'channel' => 'required|in:backend,web,app',
            ]);
            $clientDetails = DB::table('oauth_clients')->get();
            if ($clientDetails->isEmpty()) {
                $this->createClient('Laravel Personal Access Client','users');
                $this->createClient('Laravel Password Grant Client', 'users');
            }
            $id = match ($request->channel) {
                'backend' => 2,
            };
            $clientDetail = DB::table('oauth_clients')->where('id', $id)->first();
            if ($clientDetail) {
                $resultArray = [
                    'id' => $clientDetail->id,
                    'secret' => $clientDetail->secret,
                    'revoked' => $clientDetail->revoked,
                    'created_at' => $clientDetail->created_at,
                ];
                return response()->json([
                    'clientDetails' => $resultArray,
                    'status' => 'Success',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Warning',
                    'message' => 'Sorry, there are no client tokens available.',
                ], 201);
            }
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 400);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper method to create a client.
     * @param string $name
     * @param string $provider
     */
    private function createClient(string $name, string $provider): void
    {
        $isPersonalAccessClient = $name === 'Laravel Personal Access Client';

        DB::table('oauth_clients')->insert([
            'name' => $name,
            'secret' => Str::random(45),
            'provider' => $provider,
            'redirect' => 'http://localhost',
            'personal_access_client' => $isPersonalAccessClient ? 1 : 0,
            'password_client' => $isPersonalAccessClient ? 0 : 1,
            'revoked' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }//End Function
}
