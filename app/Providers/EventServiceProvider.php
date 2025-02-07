<?php

namespace App\Providers;

use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $listen = [
        Registered::class => [
            SendWelcomeEmail::class,
        ],

    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
