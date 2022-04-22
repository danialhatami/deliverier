<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse;
use App\Http\Requests\TraceRequest;
use App\Models\DeliveryTrace;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Double;

class DeliveryTraceController extends Controller
{

    use CustomResponse;

    public function tracing(TraceRequest $traceRequest, bool $firstTrace = false)
    {
        if ($firstTrace) {
            $deliveryTrace = DeliveryTrace::create($traceRequest->all());
            return $this->onSuccess($deliveryTrace, "Tracing", 200);
        } else {
            $trace = DeliveryTrace::where('delivery_request_id', $traceRequest->delivery_request_id)->first();
            if (!$trace) {
                return $this->onError(404, "Trace had not started yet");
            } else {

                $deliveryTrace = DeliveryTrace::create($traceRequest->all());
                return $this->onSuccess($deliveryTrace, "Tracing", 200);
            }
        }
    }
}
