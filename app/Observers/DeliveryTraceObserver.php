<?php

namespace App\Observers;

use App\Models\DeliveryTrace;
use App\Models\PartnerCompany;
use App\Notifications\SendRequestUpdateToPartnerViaWebhook;

class DeliveryTraceObserver
{

    public function created(DeliveryTrace $deliveryTrace)
    {
        $partnerCompany = PartnerCompany::with('deliveryRequest')->where('id', $deliveryTrace->deliveryRequest->partner_company_id)->first();
        $partnerCompany->notify(new SendRequestUpdateToPartnerViaWebhook($deliveryTrace));
    }

}
