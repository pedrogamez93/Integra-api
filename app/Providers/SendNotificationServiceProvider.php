<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SendNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $currentDate = date("d-m-Y H:i");
        Log::info($currentDate);
        //dd($currentDate);
        //$notification = Notification::where('datetime', $currentDate)->update(['is_send_notification', 1]);
    }
}
