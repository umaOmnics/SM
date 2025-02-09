<?php

namespace App\Http\Controllers\LeaveTypes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Method allow to display list of all Leave Types.
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $leave_types = LeaveType::orderBy('id','DESC')->get();
            $leave_types_details = [];
            foreach($leave_types as $leave_type){
                $leave_types_details[] = $this->leaveTypesOverview($leave_type);
            }

            return response()->json([
                'data' => $leave_types_details,
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
     * Method allow to store Leave Type.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:leave_types',
                'description' => 'required',
                'max_days' => 'required'
            ]);
            $leave_type_id = LeaveType::insertGetId([
                'name' => $request->name,
                'description' => $request->description,
                'max_days' => $request->max_days,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $leave_type = LeaveType::where('id',$leave_type_id)->first();
            $leave_type_details = $this->leaveTypesOverview($leave_type);
            return response()->json([
                'data' => $leave_type_details,
                'status' => 'Success',
                'message' => 'Leave type added successfully',
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
     * Method allow to show all the leaveTypesOverview.
     * @param $vendor
     * @return JsonResponse|array
     */
    public function vendorsOverview($leave_type): JsonResponse|array
    {
        $leave_type_array = [];
        if(!empty($leave_type)){
            $leave_type_array = [
                'id' => $leave_type->id,
                'name' => $leave_type->name,
                'description' => $leave_type->address,
                'max_days' => $leave_type->max_days,
                'created_at' => $leave_type->created_at,
                'updated_at' => $leave_type->updated_at,
                'deleted_at' => $leave_type->deleted_at,
            ];
        }
        return $leave_type_array;
    }

    /**
     * Method allow to show the Leave Type.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (LeaveType::where('id',$id)->exists()){
                $leave_type = LeaveType::where('id',$id)->first();
                $query = $this->leaveTypesOverview($leave_type);
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
     * Method allow to update leave Types.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $leave_type = LeaveType::find($id);

            if (!$leave_type) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Validate the request
            $request->validate([
                'name' => ['required', 'string', Rule::unique('leave_types', 'name')->ignore($leave_type->id)],
                'descrition' => 'required',
            ]);

            // Update the academic name and save
            $leave_type->name = $request->name;
            $leave_type->descrition = $request->descrition;
            $leave_type->max_days = $request->max_days;
            $leave_type->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if($leave_type->save()){
                $updated_leave_type = LeaveType::where('id',$id)->first();
                $leave_type_details = $this->leaveTypesOverview($updated_leave_type);
            }

            return response()->json([
                'data' => $leave_type_details,
                'status' => 'Success',
                'message' => 'The Leave type updated successfully',
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred while updating the item.',
            ], 500);
        }
    } // End Function


    /**
     * Method allow to soft delete the particular leave type.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (LeaveType::where('id',$id)->exists()){
                LeaveType::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Leave Type deleted successfully',
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
     * Method allow to soft delete the set of Leave types.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function massDelete(Request $request):JsonResponse
    {
        try {
            if (!empty($request->leave_type_id)) {
                foreach ($request->leave_type_id as $leave_type_id) {
                    $leave_type = LeaveType::findOrFail($leave_type_id);
                    $leave_type->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The leave types deleted successfully',
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
