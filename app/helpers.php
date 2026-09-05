<?php

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\Setting;
use App\Models\Points;
use Illuminate\Support\Facades\Http;

use Carbon\Carbon;

if (!function_exists('generateStaffId')) {
    function generateStaffId()
    {
        do {
          $staffId = rand(100000, 999999);
        } while (Pos::where('staff_id', $staffId)->exists());

        return $staffId;
    }
}

if (!function_exists('date_formet')) {

    /**
     * Format date like: 28 Jan 2029
     *
     * @param string|datetime $date
     * @return string|null
     */
    function date_formet($date)
    {
        if (!$date) {
            return null;
        }

        return Carbon::parse($date)->format('d M Y');
    }
}

if (!function_exists('email_temp')) {
    function email_temp($name)
    {
        return EmailTemplate::where('name', $name)->first();
    }
}

if (!function_exists('send_email')) {

    function send_email(string $to, string $templateName, array $data = [])
    {
        try {

            Mail::to($to)->send(
                new OtpMail($templateName, $data)
            );

            return true;
        } catch (\Exception $e) {

            return false;
        }
    }
}

if (!function_exists('send_whatsapp_otp')) {

    function send_whatsapp_otp($phone, $otp)
    {
        $message = "Your OTP is: " . $otp;

        $phone = '91' . $phone;

        $url = "https://wa.me/" . $phone . "?text=" . urlencode($message);

        return $url;
    }

    if (!function_exists('setting')) {
        function setting($slug)
        {
            return Setting::where('slug', $slug)->first();
        }
    }
}


if (!function_exists('generateBreadcrumb')) {
    function generateBreadcrumb()
    {
        $routeName = request()->route()->getName();

        $segments = explode('.', $routeName);

        $breadcrumb = [];
        $url = url('/');

        foreach ($segments as $segment) {
            $url .= '/' . $segment;

            $breadcrumb[] = [
                'name' => ucfirst(str_replace('_', ' ', $segment)),
                'url' => $url
            ];
        }

        return $breadcrumb;
    }
}

if (!function_exists('add_reward_points')) {

    function add_reward_points($user_id, $order_id, $order_amount)
    {
        $setting = \App\Models\Points::latest()->first();

        if (!$setting) {
            return false;
        }

        if ($order_amount < $setting->min_order_amount) {
            return false;
        }

        $earnedAmount = ($order_amount * $setting->reward_percent) / 100;

        $points = $earnedAmount / $setting->point_value;
        $finalPoints = floor($points); 

        $expiryDate = \Carbon\Carbon::now()->addDays($setting->expiry_days);

        \App\Models\Wallet::create([
            'user_id' => $user_id,
            'order_id' => $order_id,
            'type' => 'credit',
            'points' => $finalPoints,
            'description' => 'Points earned on order',
            'expiry_date' => $expiryDate,
        ]);

        return true;
    }
}

