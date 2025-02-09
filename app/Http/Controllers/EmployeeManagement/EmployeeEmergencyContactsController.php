<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\EmployeeEmergencyContacts;
use App\Models\EmployeePromotions;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeEmergencyContactsController extends Controller
{
    /**
     * Method allow to display list of all Contacts.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $query = EmployeeEmergencyContacts::all();

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
     * Method allow to show the particular Contact details.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id): JsonResponse
    {
        try {
            if(EmployeeEmergencyContacts::where('id',$id)->exists()) {
                $data=EmployeeEmergencyContacts::find($id);
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
            ]);
            $data_id = DB::table('employee_emergency_contacts')->insertGetId([
                'employee_id' => $request->employee_id,
                'contact_name' => $request->contact_name,
                'relation_ship' => $request->relation_ship,
                'phone' => $request->phone,
                'address' => $request->address,
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
     * Method allows to update Contact
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
                $data = EmployeeEmergencyContacts::findOrFail($id);
                $data->update([
                    'employee_id' => $request->employee_id,
                    'contact_name' => $request->contact_name,
                    'relation_ship' => $request->relation_ship,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'created_at' => now(),
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
     * Method allow to destroy Contacts
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            if (EmployeeEmergencyContacts::where('id', $id)->exists()) {
                $data = EmployeeEmergencyContacts::find($id);
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
