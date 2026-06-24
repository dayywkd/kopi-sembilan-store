<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Order::where('status', 'Awaiting Payment')
        ->where('created_at', '<', Carbon::now()->subHours(24))
        ->update(['status' => 'Cancelled']);
})->hourly();
