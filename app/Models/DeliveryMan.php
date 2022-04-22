<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMan extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class);
    }

    protected $fillable = [
        'name',
        'national_code',
        'user_id',
    ];

}
