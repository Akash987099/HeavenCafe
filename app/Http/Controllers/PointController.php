<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Points;

class PointController extends Controller
{
    protected $points;

    public function __construct()
    {
        $this->points = new Points();
    }

    public function index()
    {
        $points = $this->points->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('points.index', compact('points'));
    }

    public function add()
    {
        return view('points.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'reward_percent' => 'required|numeric',
            'point_value' => 'required|numeric',
            'max_redeem_percent' => 'required|numeric',
            'min_order_amount' => 'required|numeric',
            'expiry_days' => 'required|date|after_or_equal:today',
        ]);

        $this->points->create([
            'reward_percent' => $request->reward_percent,
            'point_value' => $request->point_value,
            'max_redeem_percent' => $request->max_redeem_percent,
            'min_order_amount' => $request->min_order_amount,
            'expiry_days' => $request->expiry_days,
        ]);

        return redirect()->back()->with('success', 'Points added successfully.');
    }

    public function edit($id)
    {
        $point = $this->points->findOrFail($id);
        return view('points.edit', compact('point'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'reward_percent' => 'required|numeric',
            'point_value' => 'required|numeric',
            'max_redeem_percent' => 'required|numeric',
            'min_order_amount' => 'required|numeric',
            'expiry_days' => 'required|date|after_or_equal:today',
        ]);

        $id = $request->id;

        $point = $this->points->findOrFail($id);
        $point->update([
            'reward_percent' => $request->reward_percent,
            'point_value' => $request->point_value,
            'max_redeem_percent' => $request->max_redeem_percent,
            'min_order_amount' => $request->min_order_amount,
            'expiry_days' => $request->expiry_days,
        ]);

        return redirect()->back()->with('success', 'Points updated successfully.');
    }
}
