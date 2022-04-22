<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryTrace extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_request_id',
        'delivery_man_lat',
        'delivery_man_long',
    ];

    public function deliveryRequest()
    {
        return $this->hasOne(DeliveryRequest::class, 'id', 'delivery_request_id');
    }
}
