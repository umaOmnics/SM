<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Designations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class DesignationsController extends Controller
{
    /**
     * Method allow to display list of all Designations.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $query = Designations::all();

            return response()->json([
                'data' => $query,
                'message' => 'Success',
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
            if(Designations::where('id',$id)->exists()) {
                $designation=Designations::find($id);
                return response()->json([
                    'user' => $designation,
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
     * Method allow to create a Designation
     * @param Request $request
     * @return JsonResponse
     */
    public function createDesignation(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:designations|max:255',
            ]);
            $designation = Designations::create([
                'name' => $validatedData['name'],
                'created_at' => now(),
            ]);
            return response()->json([
                'message' => 'Designation created successfully',
                'designation' => $designation,
            ]);
        }
        catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }//End Function

    /**
     * Method allows to update Designation
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function updateDesignation(Request $request, $id): JsonResponse
    {
        try {
            if (Designations::find($id)) {
                $validatedData = $request->validate([
                    'name' => 'required|unique:designations,name,' . $id . '|max:255',
                ]);
                $designation = Designations::findOrFail($id);
                $designation->update([
                    'name' => $validatedData['name'],
                    'updated_at' => now(),
                ]);
                return response()->json([
                    'message' => 'Designation updated successfully',
                    'designation' => $designation,
                ]);
            }else{
                return response()->json([
                    'status' => 'Error',
                    'message' => 'There is no relevant information for selected query',
                ],500);
            }
        } catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }//End Function

    /**
     * Method allow to destroy Designation
     * @param $id
     * @return JsonResponse
     */
    public function destroyDesignation($id): JsonResponse
    {
        try {
            if (Designations::where('id', $id)->exists()) {
                $designation = Designations::find($id);
                $designation->delete();
                return response()->json([
                    'message' => 'Designation deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'There is no relevant information for selected query',
                ], 210);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Designation not found'
            ], 404);
        }
    }//End Function
}
