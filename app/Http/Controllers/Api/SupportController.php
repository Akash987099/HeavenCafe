<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;
use App\Models\State;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\Block;
use App\Models\village;

class SupportController extends Controller
{
    protected $country;
    protected $state;
    protected $disctrict;
    protected $tehsil;
    protected $block;
    protected $village;

    public function __construct()
    {
        $this->country = new Countries();
        $this->state = new State();
        $this->disctrict = new District();
        $this->tehsil = new Tehsil();
        $this->block  = new Block();
        $this->village = new village();
    }

    public function country()
    {
        $country = $this->country->select('id', 'country_name', 'country_code', 'country_short', 'currency_name', 'currency_code', 'currency_symbol', 'currency_rate')->get();

        if (!$country) {
            return response()->json([
                'status' => false,
                'message'   => 'no record avialable!'
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message'   => 'Success!',
            'data'   => $country
        ], 200);
    }

    public function state($countryID)
    {
        if (!$countryID) {
            return response()->json([
                'status' => false,
                'message'   => 'Country id required!'
            ], 200);
        }

        $state = $this->state->where('country_id', $countryID)->select('id', 'name', 'short_name')->get();

        if (!$state) {
            return response()->json([
                'status' => false,
                'message'   => 'No record available!'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message'   => 'Success!',
            'data'   => $state
        ], 200);
    }

    public function district($stateID)
    {
        if (!$stateID) {
            return response()->json([
                'status' => false,
                'message'   => 'State id required!'
            ], 200);
        }

        $disctrict = $this->disctrict->where('state_id', $stateID)->select('id', 'name')->get();

        if (!$disctrict) {
            return response()->json([
                'status' => false,
                'message'   => 'No record available!'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message'   => 'Success!',
            'data'   => $disctrict
        ], 200);
    }

    public function tehsil($districtID)
    {
        if (!$districtID) {
            return response()->json([
                'status' => false,
                'message'   => 'Tehsil id required!'
            ], 200);
        }

        $tehsil = $this->tehsil->where('district_id', $districtID)->select('id', 'name')->get();

        if (!$tehsil) {
            return response()->json([
                'status' => false,
                'message'   => 'No record available!'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message'   => 'Success!',
            'data'   => $tehsil
        ], 200);
    }

    public function block($tehsilID)
    {
        if (!$tehsilID) {
            return response()->json([
                'status' => false,
                'message'   => 'Tehsil id required!'
            ], 200);
        }

        $block = $this->block->where('tehsil_id', $tehsilID)->select('id', 'name')->get();

        if (!$block) {
            return response()->json([
                'status' => false,
                'message'   => 'No record available!'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message'   => 'Success!',
            'data'   => $block
        ], 200);
    }

    public function village($blockID)
    {
        if (!$blockID) {
            return response()->json([
                'status' => false,
                'message'   => 'Tehsil id required!'
            ], 200);
        }

        $village = $this->village->where('block_id', $blockID)->select('id', 'name')->get();

        if (!$village) {
            return response()->json([
                'status' => false,
                'message'   => 'No record available!'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message'   => 'Success!',
            'data'   => $village
        ], 200);
    }
}
