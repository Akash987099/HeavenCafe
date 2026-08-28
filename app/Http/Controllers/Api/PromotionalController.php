<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotional;

class PromotionalController extends Controller
{
    protected $promo;

    public function __construct()
    {
        $this->promo = new Promotional();
    }

    public function promotional()
    {
        $promo = $this->promo
            ->select('name', 'url_link', 'image')
            // ->wgere('status', 1)
            ->orderBy('position', 'desc')
            ->get();

        if ($promo->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No record available',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Record fetched successfully',
            'data'    => $promo
        ], 200);
    }
}
