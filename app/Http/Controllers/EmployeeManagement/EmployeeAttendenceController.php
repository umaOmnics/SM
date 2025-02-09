<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Models\EmployeeAttendence;
use App\Models\StudentGrades;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Http\Controllers\Controller;
class EmployeeAttendenceController extends Controller
{
    /**
     * Method allow to display list of all designations or single academic_name.
     * @return JsonResponse
     * @throws Exception
     */
    public function index()
    {
        try {
            $details = EmployeeAttendence::orderBy('id','DESC')->get();
            return response()->json([
                'data' => $details,
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
     * Method allow to store or create the new Designations.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        try {
            EmployeeAttendence::insertGetId([
                'emp_id' => $request->emp_id,
                'date' => $request->date,
                'status' => $request->status,
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
            if (EmployeeAttendence::where('id',$id)->exists()){
                $details = EmployeeAttendence::where('id',$id)->first();
                return response()->json([
                    'data' => $details,
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
            $details = EmployeeAttendence::find($id);

            if (!$details) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Update the academic name and save
            $details->emp_id = $request->emp_id;
            $details->date = $request->date;
            $details->status = $request->status;
            $details->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $details->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'employee attendence has updated successfully',
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
            if (EmployeeAttendence::where('id',$id)->exists()){
                EmployeeAttendence::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The employee attendence is deleted successfully',
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
     * Method allow to soft delete the set of designations.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function massDelete(Request $request):JsonResponse
    {
        try {
            if (!empty($request->emp_attendence_ids)) {
                foreach ($request->emp_attendence_id as $emp_attendence_id) {
                    $details = EmployeeAttendence::findOrFail($emp_attendence_id);
                    $details->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The employee attendence are deleted successfully',
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
}
