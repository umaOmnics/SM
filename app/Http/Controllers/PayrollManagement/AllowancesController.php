<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Models\Allowances;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AllowancesController extends Controller
{
    /**
     * Method allow to display list of all Allowances.
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $allowances = Allowances::orderBy('id','DESC')->get();
            $allowance_details = [];
            foreach($allowances as $allowance){
                $allowance_details[] = $this->allowanceOverview($allowance);
            }

            return response()->json([
                'data' => $allowance_details,
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
     * Method allow to store vendor.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'allowance' => 'required|string|unique:allowances',
                'description' => 'required'
            ]);
            $allowance_id = Allowances::insertGetId([
                'allowance' => $request->allowance,
                'description' => $request->description,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $allowance = Allowances::where('id',$allowance_id)->first();
            $allowance_details = $this->allowanceOverview($allowance);
            return response()->json([
                'data' => $allowance_details,
                'status' => 'Success',
                'message' => 'Item added successfully',
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
     * Method allow to show all the Allowancess overview.
     * @param $allowance
     * @return JsonResponse|array
     */
    public function allowanceOverview($allowance): JsonResponse|array
    {
        $allowance_array = [];
        if(!empty($allowance)){
            $allowance_array = [
                'id' => $allowance->id,
                'allowance' => $allowance->allowance,
                'description' => $allowance->description,
                'created_at' => $allowance->created_at,
                'updated_at' => $allowance->updated_at,
                'deleted_at' => $allowance->deleted_at
            ];
        }
        return $allowance_array;
    }

    /**
     * Method allow to show the allowance.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (Allowances::where('id',$id)->exists()){
                $allowance = Allowances::where('id',$id)->first();
                $query = $this->allowanceOverview($allowance);
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
     * Method allow to update Allowances.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $allowance = Allowances::find($id);

            if (!$allowance) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Validate the request
            $request->validate([
                'allowance' => ['required', 'string', Rule::unique('allowances', 'allowance')->ignore($allowance->id)],
                'description' => 'required'
            ]);

            // Update the academic allowance and save
            $allowance->allowance = $request->allowance;
            $allowance->description = $request->description;
            $allowance->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if($allowance->save()){
                $updated_allowance = Allowances::where('id',$id)->first();
                $allowance_details = $this->allowanceOverview($updated_allowance);
            }

            return response()->json([
                'data' => $allowance_details,
                'status' => 'Success',
                'message' => 'The allowance updated successfully',
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
     * Method allow to soft delete the particular allowance.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (Allowances::where('id',$id)->exists()){
                Allowances::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The allowance deleted successfully',
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
            if (!empty($request->allowance_id)) {
                foreach ($request->allowance_id as $allowance_id) {
                    $allowance = Allowances::findOrFail($allowance_id);
                    $allowance->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The allowances deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Please select at least one vendor to delete'
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
