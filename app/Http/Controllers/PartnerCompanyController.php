<?php

namespace App\Http\Controllers;

use App\Models\PartnerCompany;
use App\Models\User;
use Illuminate\Http\Request;

class PartnerCompanyController extends Controller
{
    public function findByUserId(int $userId)
    {
        return PartnerCompany::where('user_id', '=', $userId)->first();
    }
}
