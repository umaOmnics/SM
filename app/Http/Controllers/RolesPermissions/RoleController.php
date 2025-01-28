<?php

namespace App\Http\Controllers\RolesPermissions;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Nette\Schema\ValidationException;
use Exception;

class RoleController extends Controller
{
    /**
     * Method allow to display list of all roles
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $roles = Roles::all();
            $result = $this->getRoleDetails($roles);
            return response()->json([
                'rolesDetails' => $result,
                'message' => 'Success',
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function

    /**
     * Helper method allows to get details of the roles along with the users
     * return array
     **/
    public function getRoleDetails($roles):array
    {
        $result = array();
        if (!empty($roles)){
            foreach ($roles as $role){
                $role_users = $role->users;
                $users_array = array();
                if (!empty($role_users)) {
                    foreach ($role_users as $role_user) {
                        $users_array[] = [
                            'name' => $role_user->first_name . ' ' . $role_user->last_name,
                            'email' => $role_user->email,
                        ];
                    }
                }
                $role_resources = $role->resources;
                $permissions_array = array();
                if (!empty($role_resources)){
                    foreach ($role_resources as $role_resource){
                        $permissions_array[] = [
                            'resource_id' => $role_resource->id,
                            'resource_name' => $role_resource->name,
                            'resource_slug' => $role_resource->slug,
                            'permission_id' => $role_resource->pivot->permissions_id,
                        ];
                    }
                }
                $roles_array = [
                    'id' => $role->id,
                    'name' => $role->name,
                    'slug' => $role->slug,
                    'count_of_users' => count($role_users),
                ];
                $result[] = array_merge($roles_array, ['users' => $users_array], ['resources' => $permissions_array]);
            }
        }
        return $result;
    } // End Function

    /**
     * Method allow to store or create the new Role.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request):JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:roles'
            ]);
            $convertedString = $this->convertToEnglish($request->name);
            $slug = Str::slug($convertedString, '-');
            $role_id = DB::table('roles')->insertGetId([
                'name' => $request->name,
                'slug' => $slug,
            ]);
            return response()->json([
                'status' => 'Success',
                'message' => 'Role is added successfully',
            ],200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function

    /**
     * Method allow to show the single role details
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function show($id):JsonResponse
    {
        try {
            if (Roles::where('id', $id)->exists()){
                $role = Roles::where('id', $id)->get();
                $result = array();
                $roles_array = $this->getRoleDetails($role);
                foreach ($roles_array as $roles) {
                    $result = $roles;
                }
                return response()->json([
                    'rolesDetails' => $result,
                    'message' => 'Role is added successfully',
                ],200);
            } else {
                return response()->json([
                    'success' => 'No Content',
                    'message' => 'There is no relevant information for selected query'
                ],210);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function

    /**
     * Method allow to update the name of the particular group.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id):JsonResponse
    {
        try {
            $role = Roles::where('id',$id)->first();
            if($role->slug == 'super-administrator')
            {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Super administrator role can not be updated'
                ],500);
            }
            $request->validate([
                'name' => ['required','string', Rule::unique('roles', 'name')->ignore($role->id)]
            ]);
            if (Roles::where('id',$id)->exists()){
                $convertedString = $this->convertToEnglish($request->name);
                $slug = Str::slug($convertedString, '-');
                DB::table('roles')->where('id', $id)
                    ->update(['name' => $request->name, 'slug' => $slug]);
                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Role is updated successfully',
                ],200);
            } else{
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for selected query'
                ],210);
            }
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception,
            ], 500);
        }
    } // End Function

    /**
     * Method allow to update resource permissions of the role.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function updateRolesResources(Request $request, $id):JsonResponse
    {
        try {
            $role=Roles::find($id);
            if($role->slug == 'super-administrator')
            {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Super administrator permissions can not be updated'
                ],500);
            }
            if (Roles::where('id', $id)->exists()){
                $role = Roles::where('id', $id)->first();
                if ($role->id != 1){
                    foreach ($request->roles_resources as $roles_resource){
                        $resources_id = $roles_resource['resource_id'];
                        foreach ($roles_resource['permissions_id'] as $permission_id){
                            $role->resources()->attach($resources_id, ['permissions_id' => $permission_id]);
                        }
                    }
                }
                if ($request->name != null){
                    $convertedString = $this->convertToEnglish($request->name);
                    $slug = Str::slug($convertedString, '-');
                    DB::table('roles')->where('id', $id)->update([
                        'name' => $request->name,
                        'slug' => $slug,
                    ]);
                }
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Resource permissions had been updated successfully',
                ],200);
            } else {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for selected query'
                ],210);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ], 500);
        }
    } // End Function

    /**
     * Method allow to delete resource permissions of the role.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteRolesResources(Request $request, $id): JsonResponse
    {
        try {
            if (Roles::where('id', $id)->exists()) {
                $role = Roles::where('id', $id)->first();
                if ($role->id != 1) {
                    foreach ($request->roles_resources as $roles_resource) {
                        $resources_id = $roles_resource['resource_id'];
                        if (is_array($roles_resource['permissions_id'])) {
                            foreach ($roles_resource['permissions_id'] as $permission_id) {
                                DB::table('roles_resources')
                                    ->where('roles_id', $role->id)
                                    ->where('resources_id', $resources_id)
                                    ->where('permissions_id', $permission_id)
                                    ->delete();
                            }
                        } else {
                            DB::table('roles_resources')
                                ->where('roles_id', $role->id)
                                ->where('resources_id', $resources_id)
                                ->where('permissions_id', $roles_resource['permissions_id'])
                                ->delete();
                        }
                    }
                }
                if($role->slug == 'super-administrator')
                {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Super administrator permissions can not be deleted'
                    ],500);
                }
                if ($request->name != null) {
                    $convertedString = $this->convertToEnglish($request->name);
                    $slug = Str::slug($convertedString, '-');
                    DB::table('roles')->where('id', $id)->update([
                        'name' => $request->name,
                        'slug' => $slug,
                    ]);
                }
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Resource permissions have been updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 210);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ], 500);
        }
}//End Function

    /**
     * Method allow to delete the particular role.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (Roles::where('id',$id)->exists()){
                if ($id != 1) {
                    Roles::where('id', $id)->delete();

                    return response()->json([
                        'status' => 'Success',
                        'message' => 'The Role is deleted successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Cannot delete the super administrator'
                    ],422);
                }
            }else{
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for selected query'
                ],210);
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
     * Method allows to convert the given strings into english
     * @param $string
     * @return false|string
     */
    private function convertToEnglish($string)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    }
}
