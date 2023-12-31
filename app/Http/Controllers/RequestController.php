<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryResource;
use App\Models\Customer;
use App\Models\Request as RequestModel;
use App\Rules\LocationRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RequestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/request/register",
     *     summary="Register request by intermediaries",
     *     tags={"Request"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="sender_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sender_location",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sender_address",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sender_mobile",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="recipient_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="recipient_location",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="recipient_address",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="recipient_mobile",
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
    /**
     * Register request by intermediaries
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validateRequest = Validator::make($request->all(),
            [
                // sender
                'sender_name' => 'required',
                'sender_location' => ['required', new LocationRule],
                'sender_address' => 'required',
                'sender_mobile' => 'required',
                // recipient
                'recipient_name' => 'required',
                'recipient_location' => ['required', new LocationRule],
                'recipient_address' => 'required',
                'recipient_mobile' => 'required',
            ]);

        if ($validateRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateRequest->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        try {
            DB::beginTransaction();
            $sender = $this->_saveCustomer('sender');
            $recipient = $this->_saveCustomer('recipient');
            if ($sender->id === $recipient->id)
                throw new \Exception('Sender and recipient cannot be equal!');
            $requestModel = RequestModel::create([
                'intermediary_id' => Auth::id(),
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "Not saved error: {$e->getMessage()}",
            ], Response::HTTP_BAD_REQUEST);
        }
        if ($requestModel instanceof RequestModel) {
            return response()->json([
                'request' => $requestModel,
                'message' => 'Request registered successfully',
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'status' => false,
            'message' => "Not saved: Unknown Error",
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @OA\Delete(
     *     path="/request/{id}",
     *     summary="Cancel request by intermediaries",
     *     tags={"Request"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Request ID",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Canceled successfully"
     *     )
     * )
     */
    /**
     * Cancel request by intermediaries
     * @param RequestModel $requestModel
     * @return JsonResponse
     */
    public function cancel(RequestModel $requestModel)
    {
        try {
            $requestModel->status = RequestModel::STATUS_CANCELED;
            $requestModel->save();
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Not saved error: {$e->getMessage()}",
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'request' => $requestModel,
            'message' => 'Request canceled successfully',
        ], Response::HTTP_OK);
    }

    private function _saveCustomer($prefix)
    {
        $location = request()->get("{$prefix}_location");
        list($lat, $lng) = explode(',', $location);
        return Customer::firstOrCreate([
            'name' => request()->get("{$prefix}_name"),
            'location' => DB::raw("ST_GeomFromText('POINT($lat $lng)')"),
            'address' => request()->get("{$prefix}_address"),
            'mobile' => request()->get("{$prefix}_mobile"),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/requests",
     *     summary="get list of requests for deliveries",
     *     tags={"Request"},
     *     @OA\Response(
     *         response=200,
     *         description="Requests array"
     *     )
     * )
     */
    /**
     * get list of requests for deliveries
     * @return AnonymousResourceCollection
     */
    public function requests()
    {
        $requests = RequestModel::where('status', 'registered')
            ->whereNull('delivery_id')
            ->orderByDesc('id')->get();

        return DeliveryResource::collection($requests);
    }

    /**
     * @OA\Patch(
     *     path="/request/accept/{id}",
     *     summary="Accept request by deliveries",
     *     tags={"Request"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Request ID",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Accepted successfully"
     *     )
     * )
     */
    /**
     * Accept request by intermediaries
     * @param RequestModel $requestModel
     * @return JsonResponse
     */
    public function accept(RequestModel $requestModel)
    {
        return $this->_deliveryAction(RequestModel::STATUS_ACCEPTED, $requestModel);
    }

    /**
     * @OA\Patch(
     *     path="/request/sent/{id}",
     *     summary="Send request by deliveries",
     *     tags={"Request"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Request ID",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Sent successfully"
     *     )
     * )
     */
    public function sent(RequestModel $requestModel)
    {
        return $this->_deliveryAction(RequestModel::STATUS_SENT, $requestModel);
    }

    /**
     * @OA\Patch(
     *     path="/request/delivered/{id}",
     *     summary="Deliver request by deliveries",
     *     tags={"Request"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Request ID",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Delivered successfully"
     *     )
     * )
     */
    public function deliver(RequestModel $requestModel)
    {
        return $this->_deliveryAction(RequestModel::STATUS_DELIVERED, $requestModel);
    }

    /**
     * delivery actions
     * @param RequestModel $requestModel
     * @return JsonResponse
     */
    private function _deliveryAction(string $type, RequestModel $requestModel)
    {
        try {
            $requestModel->status = $type;
            if ($type === RequestModel::STATUS_ACCEPTED) {
                $requestModel->delivery_id = Auth::id();
            }
            $requestModel->save();
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Not saved error: {$e->getMessage()}",
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'message' => "Request $type successfully",
        ], Response::HTTP_OK);
    }
}
