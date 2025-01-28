<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Users\UserController;
use App\Models\Folders;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Validation\ValidationException;
class UsersController extends Controller
{
    /**
     * Method allow to display list of all Users
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $all_users = User::where('sys_admin',1)->get();
            $users_details = array();
            if(!empty($all_users)){
                foreach ($all_users as $all_user)
                {
                    $users_details[] = $this->userDetails($all_user->id);
                }
            }
            return response()->json([
                'users' => $users_details,
                'message' => 'Success'
            ], 200);
        } catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function

    /**
     * Method allow to show the particular user details.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id): JsonResponse
    {
        try {
            if(User::where('id',$id)->exists()) {
                $user_details = $this->userDetails($id);
                return response()->json([
                    'user' => $user_details,
                    'message' => 'Success'
                ],200);
            }else{
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for selected query'
                ],210);
            }
        }catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function

    /**
     * Helper method allow to retrieve the user Details
     * @param $id
     * @param null $condition
     * @return array
     * @throws Exception
     */
    public function userDetails($id, $condition = null): array
    {
        $user = User::where('id', $id)->first();
        $userDetails = [];
        if (!empty($user)) {
            if ($condition != null) {
                $user->last_login = Carbon::now()->format('Y-m-d H:i:s');
                $user->save();
            }

            $roles = [];
            if(count($user->roles) > 0) {
                foreach ($user->roles as $role) {
                    $roles[] = [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                }
            }

            $profilePhotoPath = $user->profilePhoto ? $user->profilePhoto->file_path : null;

            $userDetails = [
                'id' => $user->id,
                'firstname' => $user->first_name,
                'lastname' => $user->last_name,
                'email' => $user->email,
                'username' => $user->username,
                'has2FA' => $user->two_factor_secret != null,
                'sys_admin' => $user->sys_admin,
                'is_active' => $user->is_active,
                'last_login' => $user->last_login,
                'address' => $user->address,
                'address_extra' => $user->address_extra,
                'zipcode' => $user->zip_code,
                'city' => $user->city,
                'state' => $user->state,
                'mobile'=> $user->mobile,
                'role' => $roles,
                'profile_photo_id' => $user->profile_photo_id,
                'profile_photo_path' => $user->profile_photo_id?$profilePhotoPath:null,
                'deleted_at' => $user->deleted_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];
        }
        return $userDetails;
    }//End Function

    /**
     * Method allows to soft delete a user
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id): JsonResponse
    {
        try {
            if($id == 1)
            {
                return response()->json([
                    'status' => 'Error',
                    'message '=> 'You cannot delete super administrator'
                ],500);
            }
            if(User::find($id)->exists()) {
                $user = User::findOrFail($id);
                $superAdminRoleId = 1;
                $isUserSuperAdmin = DB::table('users_roles')
                    ->where('users_id', $user->id)
                    ->where('roles_id', $superAdminRoleId)
                    ->exists();
                $superAdminCount = DB::table('users_roles')
                    ->where('roles_id', $superAdminRoleId)
                    ->count();
                if ($isUserSuperAdmin && $superAdminCount <= 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'At least one super admin must remain.',
                    ], 500);
                }
                $accessTokens = Token::where('user_id', $user->id)->get();
                if ($accessTokens->isNotEmpty()) {
                    Token::where('user_id', $user->id)->update(['revoked' => true]);
                }
                if ($accessTokens->isNotEmpty()) {
                    foreach ($accessTokens as $accessToken) {
                        $accessToken->update(['revoked' => true]);
                        $refreshTokens = RefreshToken::where('access_token_id', $accessToken->id)->get();
                        foreach ($refreshTokens as $refreshToken) {
                            $refreshToken->update(['revoked' => true]);
                        }
                    }
                }
                $user->delete();
                $softDeletedUsers = User::onlyTrashed()
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                            'username' => $user->username,
                            'salutation' => $user->salutations ? $user->salutations->name : null,
                            'title' => $user->titles ? $user->titles->name : null,
                            'deleted_at' => $user->deleted_at,
                        ];
                    });
                return response()->json([
                    'message' => 'User soft-deleted successfully.',
                    'deleted_users' => $softDeletedUsers,
                ],200);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'There is no relevant information for selected query',
                ], 210);
            }
        }catch (Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }//End Function

    /**
     * Method allows to permanently delete a user
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            if(User::onlyTrashed()->find($id)) {
                $user = User::onlyTrashed()->findOrFail($id);
                $user->forceDelete();
                return response()->json([
                    'message' => 'User permanently deleted successfully.',
                    'force_deleted_user' => $user,
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'There is no relevant information for selected query',
                ], 210);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }//End Function

    /**
     * Method allows to restore the user
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request): JsonResponse
    {
        try {
            $user_ids = $request->ids;
            $restored_users = [];
            $not_found_users = [];
            foreach ($user_ids as $id) {
                $trashed_user = User::onlyTrashed()->find($id);

                if ($trashed_user) {
                    $trashed_user->restore();
                    $restored_users[] = $this->userDetails($id);
                } else {
                    $not_found_users[] = $id;
                }
            }
            $response_data = [
                'message' => 'Restoration process completed.',
                'restored_users' => $restored_users,
            ];
            if (!empty($not_found_users)) {
                $response_data['not_found_users'] = $not_found_users;
            }
            return response()->json([
                'status' => 'Success',
                'data' => $response_data,
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }//End Function
}
