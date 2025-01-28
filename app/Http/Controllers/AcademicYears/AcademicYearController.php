<?php

namespace App\Http\Controllers\AcademicYears;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
// use App\Http\Controllers\AcademicYears;

class AcademicYearController extends Controller
{
    /**
     * Method allow to display list of all categories or single academic_year.
     * @return JsonResponse
     * @throws Exception
     */
    public function index()
    {
        // dd('fsfsfs');
        try {
            $academic_years = AcademicYear::orderBy('id','DESC')->get();
            // $query = $this->getMasterDataDetailsOverview($academic_year);
            return response()->json([
                'data' => $academic_years,
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
     * Method allow to store or create the new Category.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'year' => 'required|string|unique:academic_years'
            ]);
            $academic_year = AcademicYear::insertGetId([
                'year' => $request->year,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'academic year is added successfully',
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
     * Method allow to delete the particular academic_year.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (AcademicYear::where('id',$id)->exists()){
                $academic_year = AcademicYear::where('id',$id)->first();
                // $query = $this->getMasterDataDetailsOverview($academic_year);
                return response()->json([
                    'data' => $academic_year,
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
     * Method allow to update the year of the particular academic_year.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // Find the academic year by ID
            $academic_year = AcademicYear::find($id);
    
            if (!$academic_year) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }
    
            // Validate the request
            $request->validate([
                'year' => ['required', 'string', Rule::unique('academic_years', 'year')->ignore($academic_year->id)],
            ]);
    
            // Update the academic year and save
            $academic_year->year = $request->year;
            $academic_year->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $academic_year->save();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'The academic year is updated successfully',
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred while updating the academic year.',
            ], 500);
        }
    } // End Function
    

    /**
     * Method allow to soft delete the particular academic_year.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (AcademicYear::where('id',$id)->exists()){
                AcademicYear::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The academic_year is deleted successfully',
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
     * Method allow to soft delete the set of Categories.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function massDelete(Request $request):JsonResponse
    {
        try {
            if (!empty($request->academic_year_id)) {
                foreach ($request->academic_year_id as $academic_year_id) {
                    $academic_year = AcademicYear::findOrFail($academic_year_id);
                    $academic_year->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Academic Year are deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Please select at least one academic_year to delete'
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

} // End Class
