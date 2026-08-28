<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    protected $setting;

    public function __construct()
    {
        $this->setting = new Setting();
    }

    public function setting($name)
    {
        // dd($name);
        $setting = $this->setting->where('slug', $name)->select('name', 'slug', 'image', 'description')->first();

        if (!$setting) {
            return response()->json([
                'status' => false,
                'message' => ' No record Found!',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Suucess!',
            'data' => $setting,
        ]);
    }

    public function settings()
    {
        try {
            $setting = $this->setting
                ->get();

            return response()->json([
                'status' => true,
                'data'   => $setting
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data'   => []
            ], 500);
        }
    }
}
