<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\EmployeePromotions;
use App\Models\Resignations;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResignationsController extends Controller
{
    /**
     * Method allow to display list of all Resignations.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $query = Resignations::all();

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
     * Method allow to show the particular resignation details.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id): JsonResponse
    {
        try {
            if(Resignations::where('id',$id)->exists()) {
                $data=Resignations::find($id);
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
     * Method allow to create a Resignation
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'employee_id' => 'required',
                'status' => 'required|in:pending,accepted,rejected'
            ]);
            $data_id = DB::table('resignations')->insertGetId([
                'employee_id' => $request->employee_id,
                'resignation_date' => $request->resignation_date,
                'last_working_date' => $request->last_working_date,
                'reason' => $request->reason,
                'status' => $request->status,
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
     * Method allows to update Resginations
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
                    'status' => 'required|in:pending,accepted,rejected'
                ]);
                $data = EmployeePromotions::findOrFail($id);
                $data->update([
                    'employee_id' => $request->employee_id,
                    'resignation_date' => $request->resignation_date,
                    'last_working_date' => $request->last_working_date,
                    'reason' => $request->reason,
                    'status' => $request->status,
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
     * Method allow to destroy Resignation
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            if (Resignations::where('id', $id)->exists()) {
                $data = Resignations::find($id);
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
