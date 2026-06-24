<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Update Pesanan #' . $this->order->transaction_id;
        
        switch ($this->order->status) {
            case 'Paid':
                $subject = 'Pembayaran Diterima - Pesanan #' . $this->order->transaction_id;
                break;
            case 'Packing':
                $subject = 'Pesanan Sedang Diproses - Pesanan #' . $this->order->transaction_id;
                break;
            case 'Shipped':
                $subject = 'Pesanan Telah Dikirim - Pesanan #' . $this->order->transaction_id;
                break;
            case 'Delivered':
                $subject = 'Pesanan Telah Diterima - Pesanan #' . $this->order->transaction_id;
                break;
            case 'Cancelled':
                $subject = 'Pesanan Dibatalkan/Kedaluwarsa - Pesanan #' . $this->order->transaction_id;
                break;
        }

        return new Envelope(
            subject: $subject . ' - Toko Kopi Sembilan',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_changed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
