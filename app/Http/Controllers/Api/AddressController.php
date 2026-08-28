<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    protected $address;

    public function __construct()
    {
        $this->address = new Address();
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country'      => 'nullable|string',
            'state'        => 'nullable|string',
            'district'     => 'nullable|string',
            'tehsil'       => 'nullable|string',
            'block'        => 'nullable|string',
            'village'      => 'nullable|string',
            'address'      => 'required|string',

            // FIXED
            'person'       => 'required|string',

            'landmark'     => 'nullable|string',
            'contact'      => 'nullable|digits_between:10,12',
            'is_default'   => 'required|boolean',

            // FIXED lowercase
            'address_type' => 'required|in:Home,Work,Other',

            // FIXED numeric
            'lat'          => 'nullable|numeric',
            'lng'          => 'nullable|numeric',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->id();
        // dd($user_id); 

        if ($request->is_default == 1) {
            Address::where('user_id', $user_id)
                ->update(['is_default' => 0]);
        }

        $distance = 0;
        $time = '0 min';

        if ($request->lat && $request->lng) {

            $result = $this->getDistanceTime($request->lat, $request->lng);

            $distance = $result['distance'];
            $time = $result['time'];
        }

        $address = Address::create([
            'user_id'    => $user_id,
            'country'    => $request->country,
            'state'      => $request->state,
            'district'   => $request->district,
            'tehsil'     => $request->tehsil,
            'block'      => $request->block,
            'village'    => $request->village,
            'address'    => $request->address,
            'person'     => $request->person,
            'contact'    => $request->contact,
            'landmark'    => $request->landmark,
            'is_default' => $request->is_default,
            'distance'   => $distance,
            'time'       => $time,
            'lat'        => $request->lat,
            'lng'        => $request->lng,
            'address_type' => $request->address_type,
            'street'      => $request->street,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Address added successfully',
            'data'    => $address,
        ], 200);
    }

    public function getDistanceTime($userLat, $userLng)
    {
        $store = DB::table('store')->first();

        if (!$store) {
            return [
                'distance' => 0,
                'time' => '0 min'
            ];
        }

        $earthRadius = 6371;

        $latFrom = deg2rad($userLat);
        $lonFrom = deg2rad($userLng);

        $latTo = deg2rad($store->latitude);
        $lonTo = deg2rad($store->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(
            sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) *
                pow(sin($lonDelta / 2), 2)
            )
        );

        $distance = $earthRadius * $angle;
        $distance = round($distance, 2);

        // Avg speed 30 KM/H
        $minutes = round(($distance / 30) * 60);

        return [
            'distance' => $distance,
            'time' => $minutes . ' min'
        ];
    }

    public function updateAddress(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'id'          => 'required|integer',
            // 'user_id'     => 'required|integer',
            'country'     => 'nullable|string',
            'state'       => 'nullable|string',
            'district'    => 'nullable|string',
            'tehsil'      => 'nullable|string',
            'block'       => 'nullable|string',
            'village'     => 'nullable|string',
            'address'     => 'required|string',
            'person'      => 'nullable|string',
            'landmark'      => 'nullable|string',
            'contact'     => 'nullable|digits_between:10,12',
            'is_default'  => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->id();

        $address = Address::where('id', $id)
            ->where('user_id', $user_id)
            ->first();

        if (!$address) {
            return response()->json([
                'status'  => false,
                'message' => 'Address not found',
            ], 404);
        }

        if ($request->is_default == 1) {
            Address::where('user_id', $user_id)
                ->where('id', '!=', $id)
                ->update(['is_default' => 0]);
        }

        $distance = 0;
        $time = '0 min';

        if ($request->lat && $request->lng) {

            $result = $this->getDistanceTime($request->lat, $request->lng);

            $distance = $result['distance'];
            $time = $result['time'];
        }

        $address->update([
            'country'    => $request->country,
            'state'      => $request->state,
            'district'   => $request->district,
            'tehsil'     => $request->tehsil,
            'block'      => $request->block,
            'village'    => $request->village,
            'address'    => $request->address,
            'person'     => $request->person,
            'contact'    => $request->contact,
            'landmark'    => $request->landmark,
            'is_default' => $request->is_default,
            'distance'   => $distance,
            'time'       => $time,
            'lat'        => $request->lat,
            'lng'        => $request->lng,
            'street'     => $request->street,
            'address_type' => $request->address_type,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Address updated successfully',
            'data'    => $address,
        ], 200);
    }

    public function deleteAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'      => 'required|integer',
            // 'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->id();

        $address = Address::where('id', $request->id)
            ->where('user_id', $user_id)
            ->first();

        if (!$address) {
            return response()->json([
                'status'  => false,
                'message' => 'Address not found',
            ], 404);
        }

        if ($address->is_default == 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Default address cannot be deleted',
            ], 403);
        }

        $address->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Address deleted successfully',
        ], 200);
    }

    public function userAddress(Request $request)
    {
        $user_id = auth()->id();

        $address = $this->address
            ->where('user_id', $user_id)
            ->orderByRaw('is_default = 1 DESC')
            ->orderBy('id', 'DESC')
            ->get();

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'no record found!'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Success!',
            'data'   => $address
        ]);
    }

    public function changeAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->id();

        $address = Address::where('id', $request->address_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$address) {
            return response()->json([
                'status'  => false,
                'message' => 'Address not found',
            ], 404);
        }

        Address::where('user_id', $user_id)
            ->update(['is_default' => 0]);

        $address->update(['is_default' => 1]);

        return response()->json([
            'status'  => true,
            'message' => 'Default address changed successfully',
            'data'    => $address
        ], 200);
    }
}
