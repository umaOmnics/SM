<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    /**
     * Method allow to display list of all Items.
     * @return JsonResponse
     * @throws Exception
     */
    public function index()
    {
        try {
            $items = Item::orderBy('id','DESC')->get();
            $item_details = [];
            foreach($items as $item){
                $item_details[] = $this->itemsOverview($item);
            }

            return response()->json([
                'data' => $item_details,
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
     * Method allow to store item.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_code' => 'required',
                'name' => 'required|string|unique:items'
            ]);
            $item_id = Item::insertGetId([
                'product_code' => $request->product_code, // Generates a random 10-character string
                'name' => $request->name,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $item = Item::where('id',$item_id)->first();
            $item_details = $this->itemsOverview($item);
            return response()->json([
                'data' => $item_details,
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
     * Method allow to show all the items overview.
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function itemsOverview($item)
    {
        $item_array = [];
        if(!empty($item)){
            $item_array = [
                'id' => $item->id,
                'product_code' => $item->product_code,
                'name' => $item->name,
                'description' => $item->description,
                'category' => $item->category,
                'quantity' => $item->quantity,
                'weight' => $item->weight,
                'height' => $item->height,
                'width' => $item->width,
                'depth' => $item->depth,
                'vendor_id' => $item->vendor_id,
                'sub_category' => $item->sub_category,
                'type' => $item->type,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
        }
        return $item_array;
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
            if (Item::where('id',$id)->exists()){
                $item = Item::where('id',$id)->first();
                $query = $this->itemsOverview($item);
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
     * Method allow to update item.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $item = Item::find($id);

            if (!$item) {
                return response()->json([
                    'status' => 'No Content',
                    'message' => 'There is no relevant information for the selected query',
                ], 404);
            }

            // Validate the request
            $request->validate([
                'name' => ['required', 'string', Rule::unique('items', 'name')->ignore($item->id)],
            ]);

            // Update the academic name and save
            $item->name = $request->name;
            $item->description = $request->description;
            $item->category = $request->category;
            $item->quantity = $request->quantity;
            $item->weight = $request->weight;
            $item->height = $request->height;
            $item->width = $request->width;
            $item->depth = $request->depth;
            $item->vendor_id = $request->vendor_id;
            $item->sub_category = $request->sub_category;
            $item->type = $request->type;
            $item->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if($item->save()){
                $updated_item = Item::where('id',$id)->first();
                $item_details = $this->itemsOverview($updated_item);
            }

            return response()->json([
                'data' => $item_details,
                'status' => 'Success',
                'message' => 'The item updated successfully',
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
            if (Item::where('id',$id)->exists()){
                Item::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The item deleted successfully',
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
            if (!empty($request->item_id)) {
                foreach ($request->item_id as $item_id) {
                    $item = Item::findOrFail($item_id);
                    $item->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The items deleted successfully',
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

} // End Class

