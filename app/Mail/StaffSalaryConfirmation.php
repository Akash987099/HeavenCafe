<?php

namespace App\Mail;

use App\Models\Pos;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffSalaryConfirmation extends Mailable
{
    use SerializesModels;

    public function __construct(public Pos $staff, public array $salary)
    {
    }

    public function build()
    {
        return $this->subject('Salary confirmation for ' . $this->salary['month_name'])
            ->view('emails.staff-salary-confirmation');
    }
}
