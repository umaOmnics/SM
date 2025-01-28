<?php

namespace App\Http\Controllers\RolesPermissions;

use App\Http\Controllers\Controller;
use App\Models\Resources;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ResourceController extends Controller
{
    /**
     * Method allows to display all Resources, Roles and Permissions assigned to access it
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        try {
            $resources = Resources::all();
            if(!empty($resources)){
                foreach ($resources as $resource) {
                    $resource_details = Resources::where('id', $resource->id)->first();
                    $resource_array[] = [
                        'id' => $resource->id,
                        'name' => $resource->name,
                        'slug' => $resource->slug,
                    ];
                }
            }
            return response()->json([
                'data' => $resource_array,
                'message' => 'Success',
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function
}
