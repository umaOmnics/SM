<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Salutations;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
class SalutationsController extends Controller
{
    /**
     * Method allow to display list of all Salutations.
     * @return JsonResponse
     * @throws Exception
     */
    public function index():JsonResponse
    {
        try {
            $query = Salutations::all();
            return response()->json([
                'data' => $query,
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
}
