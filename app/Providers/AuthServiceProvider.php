<?php

namespace App\Providers;

use App\Models\DeliveryRequest;
use App\Models\User;
use App\Policies\DeliveryRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        DeliveryRequest::class => DeliveryRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('create-user', function (User $user) {
            if ($user['role'] == 'admin') {
                return true;
            }
        });

    }
}
