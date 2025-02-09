<?php

namespace App\Http\Controllers;

use App\Models\EmployeePromotions;
use App\Models\HrEvents;
use App\Models\Resignations;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
class HrEventsController extends Controller
{
    /**
     * Method allow to display list of all HRevents.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $query = HrEvents::all();

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
     * Method allow to show the particular hrevents details.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id): JsonResponse
    {
        try {
            if(HrEvents::where('id',$id)->exists()) {
                $data=HrEvents::find($id);
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
     * Method allow to create a Hrevents
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'event_name' => 'required',
                'event_date' => 'required'
            ]);
            $data_id = DB::table('hr_events')->insertGetId([
                'event_name' => $request->event_name,
                'event_date' => $request->event_date,
                'organized_by' => $request->organized_by,
                'location' => $request->location,
                'description' => $request->description,
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
     * Method allows to update Hrevents
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
                    'event_name' => 'required',
                    'event_date' => 'required'
                ]);
                $data = EmployeePromotions::findOrFail($id);
                $data->update([
                    'event_name' => $request->event_name,
                    'event_date' => $request->event_date,
                    'organized_by' => $request->organized_by,
                    'location' => $request->location,
                    'description' => $request->description,
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
     * Method allow to destroy Hrevents
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            if (HrEvents::where('id', $id)->exists()) {
                $data = HrEvents::find($id);
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
