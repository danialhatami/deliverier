<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PartnerCompany extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'company_code',
        'webhook_url',
        'user_id',
    ];

    public function deliveryRequest()
    {
        return $this->belongsTo(DeliveryRequest::class);
    }

    public function routeNotificationForWebhook()
    {
        return $this->webhook_url;
    }
}
