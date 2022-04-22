<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse;
use App\Http\Requests\SubmitDeliveryRequest;
use App\Http\Requests\TraceRequest;
use App\Models\DeliveryRequest;
use App\Models\DeliveryTrace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DeliveryRequestController extends Controller
{
    use CustomResponse;

    protected PartnerCompanyController $partnerCompanyController;

    protected DeliveryTraceController $deliveryTraceController;


    public function __construct(PartnerCompanyController $partnerCompanyController, DeliveryTraceController $deliveryTraceController)
    {
        $this->partnerCompanyController = $partnerCompanyController;
        $this->deliveryTraceController = $deliveryTraceController;
    }


    /**
     * @return int
     */
    private function generateTrackingCode()
    {
        return rand(10000, 100000);
    }

    /**
     * @param SubmitDeliveryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(SubmitDeliveryRequest $request): \Illuminate\Http\JsonResponse
    {
        $deliveryRequest = new DeliveryRequest($request->all());
        $generatedNumber = $this->generateTrackingCode();

        if (!DeliveryRequest::where('tracking_code', '=', $generatedNumber)->get()->isEmpty()) {
            $generatedNumber = $this->$generatedNumber();
        }

        $deliveryRequest['partner_company_id'] = $this->partnerCompanyController->findByUserId(Auth::user()->id)->id;
        $deliveryRequest['tracking_code'] = $generatedNumber;
        $deliveryRequest['user_id'] = Auth::user()->id;
        $deliveryRequest->save();

        return $this->onSuccess('Deliery Request Created', $generatedNumber);
    }

    /**
     * @param $trackingCode
     * @return \Illuminate\Http\JsonResponse
     */

    public function cancel($trackingCode)
    {
        $deliveryRequest = DeliveryRequest::where('tracking_code', $trackingCode)->first();

        $response = Gate::inspect('cancel', $deliveryRequest);


        if ($deliveryRequest) {
            if ($response->allowed()) {
                if (is_null($deliveryRequest->canceled_at)) {
                    if (is_null($deliveryRequest->delivery_man_id)) {
                        $deliveryRequest->canceled_at = now();
                        $deliveryRequest->update();
                        return $this->onSuccess($trackingCode, "Request with Tracking Code [ $trackingCode ] Cancelled");
                    } else {
                        return $this->onError(406, "Request with Tracking Code [ $trackingCode ] Has Accepted By Delivery Man!");
                    }
                } else {
                    return $this->onError(406, "Request with Tracking Code [ $trackingCode ] Has Cancelled Before!");
                }
            } else {
                return $this->onError(403, (string)$response->message());
            }
        } else {
            return $this->onError(404, "Request with Tracking Code [ $trackingCode ] Doesn't Exist!");
        }

    }

    /**
     * @param int $trackingCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(int $trackingCode)
    {
        $deliveryRequest = DeliveryRequest::where('tracking_code', $trackingCode)->first();

        if ($deliveryRequest) {
            return $this->onSuccess($deliveryRequest);
        } else {
            return $this->onError(404, "Request with Tracking Code [ $trackingCode ] Doesn't Exist!");
        }
    }

    /**
     * @param int $requestDeliveryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDeliveryMan($trackingCode, TraceRequest $traceRequest)
    {
        $deliveryRequest = DeliveryRequest::where('tracking_code', $trackingCode)->lockForUpdate()->first();
        if ($deliveryRequest) {
            if ($deliveryRequest->delivery_man_id == null) {
                $deliveryRequest->delivery_man_id = Auth::user()->id;
                $traceRequest = new TraceRequest(
                    ['delivery_request_id' => $deliveryRequest->id,
                        'delivery_man_lat' => $traceRequest['delivery_man_lat'],
                        'delivery_man_long' => $traceRequest['delivery_man_long']]
                );
                $this->deliveryTraceController->tracing($traceRequest, true);
                $deliveryRequest->save();
                return $this->onSuccess($trackingCode, "You've Submitted To Delivery Request");

            } else {
                return $this->onError(500, "This request has accepted before");
            }
        } else {
            return $this->onError(423, "Try To Accept Another One");
        }
    }

    public function index()
    {
        return $this->onSuccess(DeliveryRequest::all(), "Delivery Requests");
    }

    public function deliveryStatus($trackingCode, $status)
    {
        $deliveryRequest = $this->findFirstByTrackingCode($trackingCode);

        if ($deliveryRequest) {
            $response = Gate::inspect('startAndEndDelivery', $deliveryRequest);
            if ($response->allowed()) {
                if ($status == "start") {
                    if ($deliveryRequest->delivery_start_time == null) {
                        $deliveryRequest->delivery_start_time = now();
                        $deliveryRequest->save();
                    } else {
                        return $this->onError(403, "It had started before");
                    }
                    // it doesnt start yet exception
                } else if ($status == "end") {
                    if ($deliveryRequest->delivery_end_time == null) {
                        $deliveryRequest->delivery_end_time = now();
                        $deliveryRequest->save();
                    } else {
                        return $this->onError(403, "It had finished before");
                    }
                } else {
                    return $this->onError(403, "What do you want?");
                }
                return $this->onSuccess($trackingCode, "Delivery $status");
            } else {
                return $this->onError(403, (string)$response->message());
            }
        } else {
            return $this->onError(404, "Request with Tracking Code [ $trackingCode ] Doesn't Exist!");
        }

    }


    private function findFirstByTrackingCode($trackingCode)
    {
        return DeliveryRequest::where('tracking_code', $trackingCode)->first();
    }

    protected function findFirstById($deliveryRequestId)
    {
        return DeliveryRequest::where('id', $deliveryRequestId)->first();
    }

    public function waitList()
    {
        return $this->onSuccess(DeliveryRequest::whereNull('canceled_at')
            ->whereNull('delivery_man_id')
            ->get(), "Delivery Requests");
    }

    public function acceptedList()
    {
        return $this->onSuccess(DeliveryRequest::whereNull('delivery_end_time')
            ->where('delivery_man_id', Auth::user()->id)
            ->get(), "Delivery Requests");
    }

}
