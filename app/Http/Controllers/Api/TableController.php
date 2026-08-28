<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{
    protected $table;

    public function __construct()
    {
        $this->table = new Table();
    }

    public function tableNo($tableno)
    {
        if (empty($tableno)) {
            return response()->json([
                'status' => false,
                'message' => 'Table number is required.'
            ], 400);
        }

        $tableNumber = $this->table
            ->where('table_no', $tableno)
            ->first();

        if ($tableNumber) {
            return response()->json([
                'status' => true,
                'message' => 'Table number found.',
                'data' => $tableNumber
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Table number not found.'
            ], 404);
        }
    }
}
