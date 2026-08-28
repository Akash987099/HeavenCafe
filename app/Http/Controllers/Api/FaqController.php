<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    protected $faq;

    public function __construct()
    {
        $this->faq = new Faq();
    }

    public function faq()
    {
        $faqs = $this->faq->select('name', 'description')->get();

        return response()->json([
            'status' => true,
            'message' => 'FAQ list fetched successfully',
            'data' => $faqs
        ], 200);
    }
}
