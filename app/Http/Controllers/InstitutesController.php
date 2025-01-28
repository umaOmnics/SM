<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Employees;
use App\Models\Institutes;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;
class InstitutesController extends Controller
{
    /**
     * Method allow to display list of all employees.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $all_institutes = [];
            $details = Institutes::orderBy('id','DESC')->get();
            foreach ($details as $detail) {
                $all_institutes[] = $this->instituteDetails($detail->id);
            }
            return response()->json([
                'data' => $all_institutes,
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

    public function instituteDetails($employee_id)
    {
        $result = null;
        $detail = Institutes::where('id', $employee_id)->first();
        if(!empty($detail)) {
            $result = [
                'id' => $detail->id,
                'school_name' => $detail->school_name,
                'email' => $detail->email,
                'fax' => $detail->fax,
                'website' => $detail->website,
                'phone' => $detail->phone,
                'mobile_number' => $detail->mobile_number,
                'address_1' => $detail->address_1,
                'address_2' => $detail->address_2,
                'city' => $detail->city,
                'state_id' => $detail->state_id,
                'country_id' => $detail->country_id,
                'postal_code' => $detail->postal_code,
                'created_at' => $detail->created_at,
                'updated_at' => $detail->updated_at,
            ];
        }
        return $result;
    }
    /**
     * Method allow to store or create the new Designation.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, $id = null)
    {
        try {
            $request->validate([
                'school_name' => 'required|string',
                'email' => 'required|string|unique:institutes',
            ]);

            $data = [
                'school_name' => $request->school_name,
                'email' => $request->email,
                'fax' => $request->fax,
                'website' => $request->website,
                'phone' => $request->phone,
                'mobile_number' => $request->mobile_number,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'city' => $request->city,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'postal_code' => $request->postal_code,
                'created_at' => Carbon::now(),
            ];

            if(empty($employee_id)) {
                $id = Institutes::insertGetId($data);
            } else {
                DB::table('institutes')->update($data);
            }
            $details = $this->instituteDetails($employee_id);
            return response()->json([
                'data' => $details,
                'status' => 'Success',
                'message' => 'Institute created / updated successfully',
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
            if (Institutes::where('id',$id)->exists()){
                $details = $this->instituteDetails($id);

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
            $details = Institutes::find($id);

            if (!$details) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Update the academic name and save
            $details->name = $request->name;
            $details->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $details->save();

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
            if (Institutes::where('id',$id)->exists()){
                Institutes::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'Institutes deleted successfully',
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
            if (!empty($request->institute_ids)) {
                foreach ($request->institute_id as $institute_id) {
                    $details = Institutes::findOrFail($institute_id);
                    $details->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The institutes are deleted successfully',
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
