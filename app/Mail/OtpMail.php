<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    protected $templateName;
    protected $data;

    public function __construct($templateName, array $data = [])
    {
        $this->templateName = $templateName;
        $this->data = $data;
    }

    public function build()
    {
        $template = email_temp($this->templateName);

        $subject = $template->subject ?? 'Notification';
        $body    = $template->description ?? '';

        foreach ($this->data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }

        return $this->subject($subject)->html($body);
    }
}
