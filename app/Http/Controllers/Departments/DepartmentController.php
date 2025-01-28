<?php

namespace App\Http\Controllers\Departments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
// use App\Http\Controllers\Departments;

class DepartmentController extends Controller
{
    /**
     * Method allow to display list of all departments or single academic_name.
     * @return JsonResponse
     * @throws Exception
     */
    public function index()
    {
        // dd('fsfsfs');
        try {
            $departments = Department::orderBy('id','DESC')->get();
            // $query = $this->getMasterDataDetailsOverview($department);
            return response()->json([
                'data' => $departments,
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
     * Method allow to store or create the new Department.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:departments'
            ]);
            $department = Department::insertGetId([
                'name' => $request->name,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Name is added successfully',
            ],200);

        } catch (ValidationException $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception,
            ], 500);
        }
    } // End Function

    /**
     * Method allow to delete the particular academic_name.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (Department::where('id',$id)->exists()){
                $department = Department::where('id',$id)->first();
                // $query = $this->getMasterDataDetailsOverview($department);
                return response()->json([
                    'data' => $department,
                    'message' => 'Success',
                ],200);

            }else{
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for selected query'
                ],210);
            }
        } catch (ValidationException $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception,
            ], 500);
        }
    } // End Function

    /**
     * Method allow to update the name of the particular academic_name.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // Find the academic name by ID
            $department = Department::find($id);
    
            if (!$department) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }
    
            // Validate the request
            $request->validate([
                'name' => ['required', 'string', Rule::unique('departments', 'name')->ignore($department->id)],
            ]);
    
            // Update the academic name and save
            $department->name = $request->name;
            $department->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $department->save();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'The name is updated successfully',
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred while updating the name.',
            ], 500);
        }
    } // End Function
    

    /**
     * Method allow to soft delete the particular name.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (Department::where('id',$id)->exists()){
                Department::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The name is deleted successfully',
                ],200);

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
     * Method allow to soft delete the set of departments.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function massDelete(Request $request):JsonResponse
    {
        try {
            if (!empty($request->Department_id)) {
                foreach ($request->Department_id as $department_id) {
                    $department = Department::findOrFail($department_id);
                    $department->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The name are deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Please select at least one name to delete'
                ], 422);
            }

        } catch (Exception $exception)
        {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    } // End Function

} // End Class
