<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Tags;
use App\Models\TicketManagement;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Nette\Schema\ValidationException;

class TicketManagementController extends Controller
{
    /**
     * Method allow to display list of all ticket_management.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $ticket_managements = TicketManagement::orderBy('id', 'DESC')->get();
            $ticket_management_details = [];
            foreach ($ticket_managements as $ticket_management) {
                $ticket_management_details[] = $this->ticketManagementOverview($ticket_management);
            }
            return response()->json([
                'data' => $ticket_management_details,
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
     * Method allow to return ticketManagementOverview
     * @param $ticket_management
     * @return array
     */
    public function ticketManagementOverview($ticket_management): array
    {
        $ticket_management_array = [];
        if (!empty($ticket_management)) {
            $ticket_management_array = [
                'id' => $ticket_management->id,
                'item_id' => $ticket_management->item_id,
                'quantity' => $ticket_management->quantity,
                'created_by' => $ticket_management->created_by,
                'status' => $ticket_management->status,
                'replied_by' => $ticket_management->replied_by,
                'created_at' => $ticket_management->created_at,
                'updated_at' => $ticket_management->updated_at,
                'deleted_at' => $ticket_management->deleted_at
            ];
        }
        return $ticket_management_array;

    }// End Function

    /**
     * Method allow to store or create ticket management.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request):JsonResponse
    {
        try {
            $request->validate([
                'item_id' => 'required',
                'quantity' => 'required',
                'created_by' => 'required',
                'status' => 'required',
                'replied_by' => 'required_by',
            ]);

            $ticket_management_id = TicketManagement::insertGetId([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'created_by' => $request->created_by,
                'status' => $request->status,
                'replied_by' => $request->replied_by,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            $query = TicketManagement::where('id',$ticket_management_id)->first();
            $ticket_management_details = $this->ticketManagementOverview($query);

            return response()->json([
                'data' => $ticket_management_details,
                'status' => 'Success',
                'message' => 'Ticket Management is added successfully',
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
     * Method allow to show Ticket Management
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id):JsonResponse
    {
        try {
            if (TicketManagement::where('id',$id)->exists()){
                $ticket_management = TicketManagement::where('id',$id)->first();
                $ticket_management_details = $this->ticketManagementOverview($ticket_management);
                return response()->json([
                    'data' => $ticket_management_details,
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
     * Method allow to update the name of the particular group.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id):JsonResponse
    {
        try {

            $request->validate([
                'item_id' => 'required',
                'quantity' => 'required',
                'created_by' => 'required',
                'status' => 'required',
                'replied_by' => 'required_by',
            ]);

            if (TicketManagement::where('id',$id)->exists()){
                TicketManagement::where('id',$id)->update([
                    'item_id' => $request->item_id,
                    'quantity' => $request->quantity,
                    'created_by' => $request->created_by,
                    'status' => $request->status,
                    'replied_by' => $request->replied_by,
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
                $query = TicketManagement::where('id',$id)->first();
                $ticket_management_details = $this->ticketManagementOverview($query);

                return response()->json([
                    'data' => $ticket_management_details,
                    'status' => 'Success',
                    'message' => 'The Ticket Management is updated successfully',
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
     * Method allow to soft delete the Ticket Management
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id):JsonResponse
    {
        try {
            if (TicketManagement::where('id',$id)->exists()){
                TicketManagement::where('id',$id)->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Ticket Management is deleted successfully',
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
     * Method allow to soft delete the set of Ticket Management
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function massDelete(Request $request):JsonResponse
    {
        try {
            if (!empty($request->ticket_management_id)){
                foreach ($request->ticket_management_id as $ticket_management_id)
                {
                    $ticket_management = TicketManagement::findOrFail($ticket_management_id);
                    $ticket_management->delete();
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'The Ticket Management are deleted successfully',
                ],200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Please select at least one Ticket Management to delete'
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
