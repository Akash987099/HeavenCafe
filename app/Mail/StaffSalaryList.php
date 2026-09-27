<?php

namespace App\Mail;

use App\Models\Pos;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class StaffSalaryList extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Pos $manager,
        public Carbon $monthStart,
        public Collection $salaries,
    ) {
    }

    public function build()
    {
        return $this->subject('Staff salary list - ' . $this->monthStart->format('F Y'))
            ->view('emails.staff-salary-list');
    }
}
