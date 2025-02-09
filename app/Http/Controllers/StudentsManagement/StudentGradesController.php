<?php

namespace App\Http\Controllers\StudentsManagement;

use App\Models\Designations;
use App\Models\StudentGrades;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Http\Controllers\Controller;
class StudentGradesController extends Controller
{
    /**
     * Method allow to display list of all designations or single academic_name.
     * @return JsonResponse
     * @throws Exception
     */
    public function index()
    {
        try {
            $details = StudentGrades::orderBy('id','DESC')->get();
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
             StudentGrades::insertGetId([
                'student_id' => $request->student_id,
                'academic_year' => $request->academic_year,
                'year' => $request->year,
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
            if (StudentGrades::where('id',$id)->exists()){
                $details = StudentGrades::where('id',$id)->first();
                // $query = $this->getMasterDataDetailsOverview($designation);
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
            $details = StudentGrades::find($id);

            if (!$details) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Update the academic name and save
            $details->student_id = $request->student_id;
            $details->academic_year = $request->academic_year;
            $details->grade = $request->grade;
            $details->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $details->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'Grades are updated successfully',
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
            if (StudentGrades::where('id',$id)->exists()){
                StudentGrades::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The grade is deleted successfully',
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
            if (!empty($request->grade_ids)) {
                foreach ($request->grade_ids as $grade_id) {
                    $details = StudentGrades::findOrFail($grade_id);
                    $details->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The grades are deleted successfully',
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
