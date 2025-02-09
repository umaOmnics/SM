<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\InventoryStock;
use App\Models\Vendor;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InventoryStockController extends Controller
{
    /**
     * Method allow to display list of all Inventory stock.
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $inventory_stocks = InventoryStock::orderBy('id','DESC')->get();
            $inventory_stock_details = [];
            foreach($inventory_stocks as $inventory_stock){
                $inventory_stock_details[] = $this->inventoryStockOverview($inventory_stock);
            }

            return response()->json([
                'data' => $inventory_stock_details,
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
     * Method allow to store inventory stock.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'item_id' => 'required|int',
                'total_stock' => 'required|int',
            ]);
            $inventory_stock_id = InventoryStock::insertGetId([
                'item_id' => $request->name,
                'total_stock' => $request->total_stock,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $inventory_stock = InventoryStock::where('id',$inventory_stock_id)->first();
            $inventory_stock_details = $this->inventoryStockOverview($inventory_stock);
            return response()->json([
                'data' => $inventory_stock_details,
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
     * Method allow to show all the inventoryStockOverview.
     * @param $inventory_stock
     * @return JsonResponse|array
     */
    public function inventoryStockOverview($inventory_stock): JsonResponse|array
    {
        $inventory_stock_array = [];
        if(!empty($inventory_stock)){
            $inventory_stock_array = [
                'id' => $inventory_stock->id,
                'item_id' => $inventory_stock->item_id,
                'total_stock' => $inventory_stock->total_stock,
                'created_at' => $inventory_stock->created_at,
                'updated_at' => $inventory_stock->updated_at
            ];
        }
        return $inventory_stock_array;
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
            if (InventoryStock::where('id',$id)->exists()){
                $inventory_stock = InventoryStock::where('id',$id)->first();
                $query = $this->inventoryStockOverview($inventory_stock);
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
     * Method allow to update inventory_stock.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $inventory_stock = InventoryStock::find($id);

            if (!$inventory_stock) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Validate the request
            $request->validate([
                'item_id' => 'required|int',
                'total_stock' => 'required|int',
            ]);

            // Update the academic name and save
            $inventory_stock->item_id = $request->item_id;
            $inventory_stock->total_stock = $request->total_stock;
            $inventory_stock->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if($inventory_stock->save()){
                $updated_inventory_stock = InventoryStock::where('id',$id)->first();
                $updated_inventory_stock_details = $this->inventoryStockOverview($updated_inventory_stock);
            }

            return response()->json([
                'data' => $updated_inventory_stock_details,
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
            if (InventoryStock::where('id',$id)->exists()){
                InventoryStock::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Inventory Stock deleted successfully',
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
     * Method allow to soft delete the set of InventoryStock.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function massDelete(Request $request):JsonResponse
    {
        try {
            if (!empty($request->inventory_stock_id)) {
                foreach ($request->inventory_stock_id as $inventory_stock_id) {
                    $inventory_stock = InventoryStock::findOrFail($inventory_stock_id);
                    $inventory_stock->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Inventory Stock deleted successfully',
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
