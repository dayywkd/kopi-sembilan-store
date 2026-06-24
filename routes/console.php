<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

Schedule::call(function () {
    $expiredOrders = Order::where('status', 'Awaiting Payment')
        ->where('created_at', '<', Carbon::now()->subHours(24))
        ->get();

    foreach ($expiredOrders as $order) {
        $order->update(['status' => 'Cancelled']);

        // Kirim email notifikasi bahwa pesanan kedaluwarsa/dibatalkan
        try {
            Mail::to($order->email)->send(new \App\Mail\OrderStatusChanged($order));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email pembatalan pesanan #{$order->transaction_id}: " . $e->getMessage());
        }
    }
})->hourly();
