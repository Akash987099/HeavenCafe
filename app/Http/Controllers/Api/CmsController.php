<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMS;

class CmsController extends Controller
{
    protected $cms;

    public function __construct()
    {
        $this->cms = new CMS();
    }

    public function index()
    {
        try {

            $cms = $this->cms
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get([
                    'id',
                    'category',
                    'name',
                    'url'
                ])
                ->groupBy('category');

            return response()->json([
                'status' => true,
                'message' => 'CMS list',
                'data' => $cms
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 500);
        }
    }

    public function show($url)
    {
        try {

            $cms = $this->cms
                ->where('status', 1)
                ->where('url', $url)
                ->first([
                    'id',
                    'name',
                    'url',
                    'description'
                ]);

            if (!$cms) {
                return response()->json([
                    'status' => false,
                    'message' => 'CMS page not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'CMS details',
                'data' => $cms
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
