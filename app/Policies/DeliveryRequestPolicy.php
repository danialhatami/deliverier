<?php

namespace App\Policies;

use App\Http\CustomResponse;
use App\Models\DeliveryRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class DeliveryRequestPolicy
{
    use HandlesAuthorization;


    public function view(User $user, DeliveryRequest $deliveryRequest)
    {
        if (Auth::user()->role != 'admin') {
            if (Auth::user()->role == 'delivery man' || Auth::user()->role == 'partner') {
                if (($user->id == $deliveryRequest->user->id)
                    || ($deliveryRequest->delivery_man_id != null && $user->id == $deliveryRequest->deliveryMan->user->id)) {
                    return Response::allow();
                }
                return Response::deny("You don't have such a request");
            } else {
                return Response::deny("You don't have access");
            }
        } else {
            return Response::allow();
        }
    }


    public function startAndEndDelivery(User $user, DeliveryRequest $deliveryRequest)
    {
        if ($user->role == 'delivery man') {
            if ($deliveryRequest->delivery_man_id != null && Auth::user()->id == $deliveryRequest->delivery_man_id) {
                return Response::allow();
            } else {
                return Response::deny("A Request Without a man");
            }
        } else {
            return Response::deny("You don't have access");
        }
    }

    public
    function cancel(User $user, DeliveryRequest $deliveryRequest)
    {
        if (Auth::user()->role == 'partner') {
            return $user->id == $deliveryRequest->user->id
                ? Response::allow()
                : Response::deny("You don't have such a request");
        } else {
            return Response::deny("You don't have access");
        }
    }

}
