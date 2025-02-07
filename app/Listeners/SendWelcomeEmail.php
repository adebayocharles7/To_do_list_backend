<?php

namespace App\Listeners;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Send welcome email to the user
        Mail::to($user->email)->send(new WelcomeEmail($user));

        //Log::info('Registered event handled for user: ' . $event->user->email);

        dump('Listener executed for user: ' . $event->user->email);
    }
}
