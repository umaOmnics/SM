<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Models\Designations;
use App\Models\Employees;
use App\Models\Students;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Http\Controllers\Controller;
class EmployeesController extends Controller
{
    /**
     * Method allow to display list of all employees.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $all_employees = [];
            $employees = Employees::orderBy('id','DESC')->get();
            foreach ($employees as $employee) {
                $all_employees[] = $this->employeeDetails($employee->id);
            }
            return response()->json([
                'data' => $all_employees,
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

    public function employeeDetails($employee_id)
    {
        $result = null;
        $employee = Employees::where('id', $employee_id)->first();
        if(!empty($employee)) {
            $result = [
                'id' => $employee->id,
                'joining_date' => $employee->joining_date,
                'category_id' => $employee->category_id,
                'designation_id' => $employee->designation_id,
                'department_id' => $employee->department_id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'dob' => $employee->dob,
                'blood_group' => $employee->blood_group,
                'phone' => $employee->phone,
                'profile_photo_url' => $employee->profile_photo_url,
                'mobile_number' => $employee->mobile_number,
                'address_1' => $employee->address_1,
                'address_2' => $employee->address_2,
                'city' => $employee->city,
                'state_id' => $employee->state_id,
                'country_id' => $employee->country_id,
                'postal_code' => $employee->postal_code,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
            ];
        }
        return $result;
    }
    /**
     * Method allow to store or create the new Designations.
     * @param Request $request
     * @param $employee_id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, $employee_id = null)
    {
        try {
            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|string|unique:employees',
            ]);

            $data = [
                'joining_date' => $request->joining_date,
                'category_id' => $request->category_id,
                'designation_id' => $request->designation_id,
                'department_id' => $request->department_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'dob' => $request->dob,
                'blood_group' => $request->blood_group,
                'phone' => $request->phone,
                'profile_photo_url' => $request->profile_photo_url,
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
                $employee_id = Employees::insertGetId($data);
            } else {
                DB::table('employees')->update($data);
            }
            $details = $this->employeeDetails($employee_id);
            return response()->json([
                'data' => $details,
                'status' => 'Success',
                'message' => 'Employee created / updated successfully',
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
            if (Employees::where('id',$id)->exists()){
                $details = $this->employeeDetails($id);

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
            $designation = Designations::find($id);

            if (!$designation) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Validate the request
            $request->validate([
                'name' => ['required', 'string', Rule::unique('designations', 'name')->ignore($designation->id)],
            ]);

            // Update the academic name and save
            $designation->name = $request->name;
            $designation->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $designation->save();

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
            if (Employees::where('id',$id)->exists()){
                Employees::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'Employee deleted successfully',
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
            if (!empty($request->employee_ids)) {
                foreach ($request->employee_ids as $employee_id) {
                    $employees = Employees::findOrFail($employee_id);
                    $employees->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The employees are deleted successfully',
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
