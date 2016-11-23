<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];
    
    public function boot(GateContract $gate) {
        $this->registerPolicies($gate);

        $gate->define('admin', function ($user) {
            return $user->admin == 1;
        });
        
        $gate->define('super-admin', function ($user) {
            return $user->superAdmin == 1;
        });
        
        
    }
}
