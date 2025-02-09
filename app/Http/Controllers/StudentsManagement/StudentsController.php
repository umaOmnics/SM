<?php

namespace App\Http\Controllers\StudentsManagement;

use App\Models\Designations;
use App\Models\Students;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
class StudentsController extends Controller
{
    /**
     * Method allow to display list of all students.
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $all_students = [];
            $students = Students::orderBy('id','DESC')->get();
            foreach ($students as $student) {
                $all_students[] = $this->studentDetails($student->id);
            }
            return response()->json([
                'data' => $all_students,
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

    public function studentDetails($student_id)
    {
        $result = null;
        $student = Students::where('id', $student_id)->first();
        if(!empty($student)) {
            $result = [
                'id' => $student->id,
                'category_id' => $student->category_id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'dob' => $student->dob,
                'blood_group' => $student->blood_group,
                'nationality' => $student->nationality,
                'religion' => $student->religion,
                'phone' => $student->phone,
                'profile_photo_url' => $student->profile_photo_url,
                'mobile_number' => $student->mobile_number,
                'address_1' => $student->address_1,
                'address_2' => $student->address_2,
                'city' => $student->city,
                'state_id' => $student->state_id,
                'country_id' => $student->country_id,
                'admission_number' => $student->admission_number,
                'joining_date' => $student->joining_date,
                'roll_number' => $student->roll_number,
                'parent_first_name' => $student->parent_first_name,
                'parent_last_name' => $student->parent_last_name,
                'relation' => $student->relation,
                'occupation' => $student->occupation,
                'parent_email' => $student->parent_email,
                'parent_phone' => $student->parent_phone,
                'parent_mobile_number' => $student->parent_mobile_number,
                'parent_address_1' => $student->parent_address_1,
                'parent_address_2' => $student->parent_address_2,
                'parent_city' => $student->parent_city,
                'parent_state_id' => $student->parent_state_id,
                'parent_country_id' => $student->parent_country_id,
                'parent_postal_code' => $student->parent_postal_code,
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ];
        }
        return $result;
    }
    /**
     * Method allow to store or create the new Designations.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, $student_id = null)
    {
        try {
            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|string|unique:students',
            ]);

            $data = [
                'category_id' => $request->category_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'dob' => $request->dob,
                'blood_group' => $request->blood_group,
                'nationality' => $request->nationality,
                'religion' => $request->religion,
                'phone' => $request->phone,
                'profile_photo_url' => $request->profile_photo_url,
                'mobile_number' => $request->mobile_number,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'city' => $request->city,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'admission_number' => $request->admission_number,
                'joining_date' => $request->joining_date,
                'roll_number' => $request->roll_number,
                'parent_first_name' => $request->parent_first_name,
                'parent_last_name' => $request->parent_last_name,
                'relation' => $request->relation,
                'occupation' => $request->occupation,
                'parent_email' => $request->parent_email,
                'parent_phone' => $request->parent_phone,
                'parent_mobile_number' => $request->parent_mobile_number,
                'parent_address_1' => $request->parent_address_1,
                'parent_address_2' => $request->parent_address_2,
                'parent_city' => $request->parent_city,
                'parent_state_id' => $request->parent_state_id,
                'parent_country_id' => $request->parent_country_id,
                'parent_postal_code' => $request->parent_postal_code,
                'created_at' => Carbon::now(),
            ];

            if(empty($student_id)) {
                $student_id = Students::insertGetId($data);
            } else {
                DB::table('students')->update($data);
            }
            $details = $this->studentDetails($student_id);
            return response()->json([
                'data' => $details,
                'status' => 'Success',
                'message' => 'Student created / updated successfully',
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
            if (Students::where('id',$id)->exists()){
                $details = $this->studentDetails($id);

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
            if (Students::where('id',$id)->exists()){
                Students::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'Student deleted successfully',
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
            if (!empty($request->students_ids)) {
                foreach ($request->students_ids as $students_id) {
                    $students = Students::findOrFail($students_id);
                    $students->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The students are deleted successfully',
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
