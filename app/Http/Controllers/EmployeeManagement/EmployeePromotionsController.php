<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\EmployeePromotions;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeePromotionsController extends Controller
{
    /**
     * Method allow to display list of all Employee promotions.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $query = EmployeePromotions::all();

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
     * Method allow to show the particular promotion details.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id): JsonResponse
    {
        try {
            if(EmployeePromotions::where('id',$id)->exists()) {
                $data=EmployeePromotions::find($id);
                return response()->json([
                    'data' => $data,
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
     * Method allow to create a Promotions
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'employee_id' => 'required',
            ]);
            $data_id = DB::table('employee_promotions')->insertGetId([
                'employee_id' => $request->employee_id,
                'previous_designation_id' => $request->previous_designation_id,
                'new_designation_id' => $request->new_designation_id,
                'previous_department_id' => $request->previous_department_id,
                'new_department_id' => $request->new_department_id,
                'promotion_date' => $request->promotion_date,
                'remarks' => $request->remarks,
                'created_at' => now(),
            ]);
            return response()->json([
                'message' => 'Record created successfully',
                'data' => $data_id,
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
     * Method allows to update Promotions
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            if (EmployeePromotions::find($id)) {
                $validatedData = $request->validate([
                    'employee_id' => 'required',
                ]);
                $data = EmployeePromotions::findOrFail($id);
                $data->update([
                    'employee_id' => $request->employee_id,
                    'previous_designation_id' => $request->previous_designation_id,
                    'new_designation_id' => $request->new_designation_id,
                    'previous_department_id' => $request->previous_department_id,
                    'new_department_id' => $request->new_department_id,
                    'promotion_date' => $request->promotion_date,
                    'remarks' => $request->remarks,
                    'updated_at' => Carbon::now(),
                ]);
                return response()->json([
                    'message' => 'Record updated successfully',
                    'data' => $data,
                ]);
            }else{
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for selected query',
                ],210);
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
     * Method allow to destroy Promotions
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            if (EmployeePromotions::where('id', $id)->exists()) {
                $data = EmployeePromotions::find($id);
                $data->delete();
                return response()->json([
                    'message' => 'Record deleted successfully',
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
                'message' => 'Record not found'
            ], 404);
        }
    }//End Function
}
