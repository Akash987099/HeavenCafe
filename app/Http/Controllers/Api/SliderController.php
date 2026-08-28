<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    protected $slider;

    public function __construct()
    {
        $this->slider = new Slider();
    }

    public function slider()
    {
        try {
            $sliders = $this->slider
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get(['id', 'name', 'image', 'link']);

            return response()->json([
                'status' => true,
                'data'   => $sliders
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data'   => []
            ], 500);
        }
    }
}
