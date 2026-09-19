<?php

namespace App\Mail;

use App\Models\CustomerOrder;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmation extends Mailable
{
    use SerializesModels;

    public function __construct(public CustomerOrder $order)
    {
    }

    public function build()
    {
        return $this->subject('Order confirmed: ' . $this->order->order_number)
            ->view('emails.customer-order-confirmation');
    }
}
