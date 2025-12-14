<?php

namespace App\Providers;

use App\Models\InternshipApplication;
use App\Policies\InternshipApplicationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        InternshipApplication::class => InternshipApplicationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}