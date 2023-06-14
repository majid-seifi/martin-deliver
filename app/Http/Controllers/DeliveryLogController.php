<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLog;
use App\Rules\LocationRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeliveryLogController extends Controller
{
    /**
     * @OA\Post(
     *     path="/delivery/log",
     *     summary="Save delivery location",
     *     tags={"Delivery"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="location",
     *                     type="string"
     *                 )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully"
     *     )
     * )
     */
    public function store(Request $request) {
        $validateRequest = Validator::make($request->all(), [
            'location' => ['required', new LocationRule],
        ]);
        if ($validateRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateRequest->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $location = $request->get('location');
        list($lat, $lng) = explode(',', $location);
        try {
            $log = DeliveryLog::create([
                'delivery_id' => Auth::id(),
                'location' => DB::raw("ST_GeomFromText('POINT($lat $lng)')"),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Not saved error: {$e->getMessage()}",
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($log instanceof DeliveryLog) {
            return response()->json([
                'log' => $log->id,
                'message' => 'Log saved successfully',
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'status' => false,
            'message' => "Not saved: Unknown Error",
        ], Response::HTTP_BAD_REQUEST);

    }
}
