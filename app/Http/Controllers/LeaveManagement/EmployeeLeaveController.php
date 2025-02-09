<?php

namespace App\Http\Controllers\EmployeeLeaves;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    /**
     * Method allow to display list of all Leave Types.
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $employee_leaves = EmployeeLeaves::orderBy('id','DESC')->get();
            $employee_leave_details = [];
            foreach($employee_leaves as $employee_leave){
                $employee_leave_details[] = $this->employeeLeavesOverview($employee_leave);
            }

            return response()->json([
                'data' => $employee_leave_details,
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
     * Method allow to store Employee Leaves.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'employee_id' => 'required',
                'leave_type_id' => 'required',
                'start_date' => 'required',
                'start_date' => 'required',
                'status' => 'required|in:pending,approved,rejected',
                'reason' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'applied_date' => 'required',
                'approved_by' => 'required',
                'rejected_by' => 'required',
            ]);
            if(!empty($request->status)){
                $status = $request->status;
            } else{
                $status = 'pending';
            }
            $leave_id = EmployeeLeaves::insertGetId([
                'employee_id'   => $request->employee_id,
                'leave_type_id' => $request->leave_type_id,
                'status'        => $status,
                'reason'        => $request->reason,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'applied_date'  => $request->applied_date,
                'approved_by'   => $request->approved_by,
                'rejected_by'   => $request->rejected_by,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $employee_leave = EmployeeLeaves::where('id',$leave_id)->first();
            $employee_leave_details = $this->employeeLeavesOverview($employee_leave);
            return response()->json([
                'data' => $employee_leave_details,
                'status' => 'Success',
                'message' => 'Employee Leave added successfully',
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
     * Method allow to show all the employeeLeavesOverview.
     * @param $vendor
     * @return JsonResponse|array
     */
    public function employeeLeavesOverview($employee_leave): JsonResponse|array
    {
        $employee_leave_array = [];
        if(!empty($employee_leave)){
            $employee_leave_array = [
                'id'            => $employee_leave->id,
                'employee_id'   => $employee_leave->employee_id,
                'leave_type_id' => $employee_leave->leave_type_id,
                'status'        => $employee_leave->status,
                'reason'        => $employee_leave->reason,
                'start_date'    => $employee_leave->start_date,
                'end_date'      => $employee_leave->end_date,
                'applied_date'  => $employee_leave->applied_date,
                'approved_by'   => $employee_leave->approved_by,
                'rejected_by'   => $employee_leave->rejected_by,
                'created_at'    => $employee_leave->created_at,
                'updated_at'    => $employee_leave->updated_at,
                'deleted_at'    => $employee_leave->deleted_at,
            ];
        }
        return $employee_leave_array;
    }

    /**
     * Method allow to show the Employee Leaves.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (EmployeeLeaves::where('id',$id)->exists()){
                $employee_leave = EmployeeLeaves::where('id',$id)->first();
                $query = $this->employeeLeavesOverview($employee_leave);
                return response()->json([
                    'data' => $query,
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
     * Method allows updating Employee Leaves.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $employee_leave = EmployeeLeaves::find($id);

            if (!$employee_leave) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Validate the request
            $request->validate([
                'employee_id'   => 'required',
                'leave_type_id' => 'required',
                'start_date'    => 'required|date',
                'end_date'      => 'required|date|after_or_equal:start_date',
                'status'        => 'required|in:pending,approved,rejected',
                'reason'        => 'required',
                'applied_date'  => 'required|date',
                'approved_by'   => 'nullable',
                'rejected_by'   => 'nullable',
            ]);

            // Update the leave request details
            $employee_leave->employee_id   = $request->employee_id;
            $employee_leave->leave_type_id = $request->leave_type_id;
            $employee_leave->status        = $request->status;
            $employee_leave->reason        = $request->reason;
            $employee_leave->start_date    = $request->start_date;
            $employee_leave->end_date      = $request->end_date;
            $employee_leave->applied_date  = $request->applied_date;
            $employee_leave->approved_by   = $request->approved_by;
            $employee_leave->rejected_by   = $request->rejected_by;
            $employee_leave->updated_at    = Carbon::now()->format('Y-m-d H:i:s');

            if ($employee_leave->save()) {
                $updated_employee_leave = EmployeeLeaves::where('id', $id)->first();
                $employee_leave_details = $this->leaveOverview($updated_employee_leave);

                return response()->json([
                    'data'    => $employee_leave_details,
                    'status'  => 'Success',
                    'message' => 'The leave request has been updated successfully.',
                ], 200);
            }

            return response()->json([
                'status'  => 'Error',
                'message' => 'Failed to update the leave request.',
            ], 500);
            
        } catch (ValidationException $exception) {
            return response()->json([
                'status'  => 'Error',
                'message' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'An error occurred while updating the item.',
            ], 500);
        }
    }



    /**
     * Method allow to soft delete the particular leave type.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (EmployeeLeaves::where('id',$id)->exists()){
                EmployeeLeaves::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The EmployeeLeave deleted successfully',
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
     * Method allow to soft delete the set of EmployeeLeaves
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function massDelete(Request $request):JsonResponse
    {
        try {
            if (!empty($request->employee_leave_id)) {
                foreach ($request->employee_leave_id as $employee_leave_id) {
                    $employee_leave = EmployeeLeaves::findOrFail($employee_leave_id);
                    $employee_leave->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Employee leave deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Please select at least one leave type to delete'
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
