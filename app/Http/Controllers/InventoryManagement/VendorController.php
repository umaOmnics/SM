<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class VendorController extends Controller
{
    /**
     * Method allow to display list of all Vendors.
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $vendors = Vendor::orderBy('id','DESC')->get();
            $vendors_details = [];
            foreach($vendors as $vendor){
                $vendors_details[] = $this->vendorsOverview($vendor);
            }

            return response()->json([
                'data' => $vendors_details,
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
                'name' => 'required|string|unique:vendors'
            ]);
            $vendor_id = Vendor::insertGetId([
                'name' => $request->name,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $vendor = Vendor::where('id',$vendor_id)->first();
            $vendor_details = $this->vendorsOverview($vendor);
            return response()->json([
                'data' => $vendor_details,
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
     * Method allow to show all the Vendors overview.
     * @param $vendor
     * @return JsonResponse|array
     */
    public function vendorsOverview($vendor): JsonResponse|array
    {
        $vendor_array = [];
        if(!empty($vendor)){
            $vendor_array = [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'address' => $vendor->address,
                'email' => $vendor->email,
                'phone_number' => $vendor->phone_number,
                'created_at' => $vendor->created_at,
                'updated_at' => $vendor->updated_at
            ];
        }
        return $vendor_array;
    }

    /**
     * Method allow to show the item details.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (Vendor::where('id',$id)->exists()){
                $vendor = Vendor::where('id',$id)->first();
                $query = $this->vendorsOverview($vendor);
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
     * Method allow to update Vendor.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $vendor = Vendor::find($id);

            if (!$vendor) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Validate the request
            $request->validate([
                'name' => ['required', 'string', Rule::unique('vendors', 'name')->ignore($vendor->id)],
            ]);

            // Update the academic name and save
            $vendor->name = $request->name;
            $vendor->address = $request->address;
            $vendor->email = $request->email;
            $vendor->phone_number = $request->phone_number;
            $vendor->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if($vendor->save()){
                $updated_vendor = Vendor::where('id',$id)->first();
                $vendor_details = $this->vendorsOverview($updated_vendor);
            }

            return response()->json([
                'data' => $vendor_details,
                'status' => 'Success',
                'message' => 'The vendor updated successfully',
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
     * Method allow to soft delete the particular vendor.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (Vendor::where('id',$id)->exists()){
                Vendor::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The vendor deleted successfully',
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
            if (!empty($request->vendor_id)) {
                foreach ($request->vendor_id as $vendor_id) {
                    $vendor = Vendor::findOrFail($vendor_id);
                    $vendor->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The vendors deleted successfully',
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
