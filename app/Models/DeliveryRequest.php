<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DeliveryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_long',
        'sender_lat',
        'sender_address',
        'sender_name',
        'sender_mobile',
        'receiver_name',
        'receiver_mobile',
        'receiver_address',
        'receiver_long',
        'receiver_lat',
        'partner_company_id'
    ];

    public function partnerCompany()
    {
        return $this->hasOne(PartnerCompany::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class);
    }

    public function deliveryTrace()
    {
        return $this->belongsToMany(DeliveryTrace::class);
    }

}
