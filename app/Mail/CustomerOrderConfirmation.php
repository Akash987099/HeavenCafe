<?php

namespace App\Mail;

use App\Models\PosOrder;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmation extends Mailable
{
    use SerializesModels;

    public function __construct(public PosOrder $order)
    {
    }

    public function build()
    {
        return $this->subject('Order confirmed: ' . $this->order->order_number)
            ->view('emails.customer-order-confirmation');
    }
}
