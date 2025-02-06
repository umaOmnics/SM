<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\ItemOrderDetails;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ItemOrderDetailsController extends Controller
{
    /**
     * Method allow to display list of all Items.
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $item_orders = ItemOrderDetails::orderBy('id','DESC')->get();
            $item_order_details = [];
            foreach($item_orders as $item){
                $item_order_details[] = $this->itemsOrderDetailsOverview($item);
            }

            return response()->json([
                'data' => $item_order_details,
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
     * Method allow to Item order details.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException|Exception
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'item_id' => 'required|int',
                'vendor_id' => 'required|int',
                'quantity' => 'required|int'
            ]);
            $item_order_details_id = ItemOrderDetails::insertGetId([
                'item_id' => $request->item_id, // Generates a random 10-character string
                'vendor_id' => $request->vendor_id,
                'quantity' => $request->quantity,
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $query = ItemOrderDetails::where('id',$item_order_details_id)->first();
            $item_order_details = $this->itemsOrderDetailsOverview($query);
            return response()->json([
                'data' => $item_order_details,
                'status' => 'Success',
                'message' => 'Item order details added successfully',
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
     * Method allow to show all the itemsOrderDetailsOverview.
     * @param $order_query
     * @return array
     */
    public function itemsOrderDetailsOverview($order_query): array
    {
        $item_order_details_array = [];
        if(!empty($order_query)){
            $item_order_details_array = [
                'id' => $order_query->id,
                'item_id' => $order_query->item_id,
                'vendor_id' => $order_query->vendor_id,
                'quantity' => $order_query->quantity,
                'order_date' => $order_query->order_date,
                'delivery_date' => $order_query->delivery_date,
                'created_at' => $order_query->created_at,
                'updated_at' => $order_query->updated_at,
                'deleted_at' => $order_query->deleted_at
            ];
        }
        return $item_order_details_array;
    }

    /**
     * Method allow to show the item order details.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (ItemOrderDetails::where('id',$id)->exists()){
                $item_order_query = ItemOrderDetails::where('id',$id)->first();
                $item_order_details = $this->itemsOrderDetailsOverview($item_order_query);
                return response()->json([
                    'data' => $item_order_details,
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
     * Method allow to update Item Order Details.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $item_order = ItemOrderDetails::find($id);

            if (!$item_order) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            $request->validate([
                'item_id' => 'required|int',
                'vendor_id' => 'required|int',
                'quantity' => 'required|int'
            ]);

            // Update the academic name and save
           $item_order->item_id = $request->item_id;
           $item_order->vendor_id = $request->vendor_id;
           $item_order->quantity = $request->quantity;
           $item_order->order_date = $request->order_date;
           $item_order->delivery_date = $request->delivery_date;
           $item_order->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if($item_order->save()){
                $updated_order_item = ItemOrderDetails::where('id',$id)->first();
                $item_order_details = $this->itemsOrderDetailsOverview($updated_order_item);
            }

            return response()->json([
                'data' => $item_order_details,
                'status' => 'Success',
                'message' => 'The item order updated successfully',
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
     * Method allow to soft delete the particular name.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (ItemOrderDetails::where('id',$id)->exists()){
                ItemOrderDetails::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The item order deleted successfully',
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
            if (!empty($request->item_order_id)) {
                foreach ($request->item_order_id as $item_order_id) {
                    $item_order = ItemOrderDetails::findOrFail($item_order_id);
                    $item_order->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The item order deleted successfully',
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
