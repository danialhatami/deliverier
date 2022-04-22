<?php

namespace App\Observers;

use App\Http\Controllers\DeliveryTraceController;
use App\Http\Requests\TraceRequest;
use App\Models\DeliveryRequest;
use App\Models\PartnerCompany;
use App\Notifications\SendRequestUpdateToPartnerViaWebhook;

class DeliveryRequestObserver
{

    public function updated(DeliveryRequest $deliveryRequest)
    {
        if ($deliveryRequest->wasChanged('delivery_man_id')) {
            $partnerCompany = PartnerCompany::where('id', $deliveryRequest->partner_company_id)->first();
            $partnerCompany->notify(new SendRequestUpdateToPartnerViaWebhook($deliveryRequest));
        }
    }
}
