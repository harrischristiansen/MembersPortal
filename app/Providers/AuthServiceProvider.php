<?php

namespace App\Providers;

use Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('permission', function ($user, $name) {
            return $user->permissions->contains('name', $name);
        });

        $gate->define('admin', function ($user) {
            return Gate::allows('permission', 'admin');
        });

        $gate->define('member-matches', function ($user, $object) {
            if (Gate::allows('admin')) {
                return 1;
            }

            return $user->id == $object->id;
        });

        $gate->define('member-owns', function ($user, $object) {
            if (Gate::allows('admin')) {
                return 1;
            }

            return $user->id == $object->member_id;
        });
    }
}
