<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaylaterController extends Controller
{
    public function index()
    {
        $paylater = DB::table('paylater_applications')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => $paylater,
        ]);
    }

    public function apply(Request $request)
    {
        $userId = auth()->id();

        $existing = DB::table('paylater_applications')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return response()->json([
                'status' => false,
                'message' => 'You have already applied for Pay Later',
                'data' => [
                    'status' => $existing->status
                ]
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'full_name'       => 'required|string|max:100',
            'email'           => 'nullable|email',
            'phone'           => 'required|digits:10',
            'dob'             => 'nullable|date',

            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'pincode'         => 'nullable|digits:6',

            'aadhaar_number'  => 'required|digits:12',
            'pan_number'      => 'required|string|size:10',

            'aadhaar_front'   => 'required|image|mimes:jpg,jpeg,png',
            'aadhaar_back'    => 'nullable|image|mimes:jpg,jpeg,png',
            'pan_card_image'  => 'required|image|mimes:jpg,jpeg,png',
            'user_photo'      => 'required|image|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (DB::table('paylater_applications')->where('aadhaar_number', $request->aadhaar_number)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Aadhaar already used'
            ], 400);
        }

        if (DB::table('paylater_applications')->where('pan_number', strtoupper($request->pan_number))->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'PAN already used'
            ], 400);
        }

        $uploadPath = public_path('uploads/kyc');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $aadhaarFront = null;
        $aadhaarBack  = null;
        $panImage     = null;
        $photo        = null;

        if ($request->hasFile('aadhaar_front')) {
            $name = time() . '_aadhaar_front_' . $request->file('aadhaar_front')->getClientOriginalName();
            $request->file('aadhaar_front')->move($uploadPath, $name);
            $aadhaarFront = 'uploads/kyc/' . $name;
        }

        if ($request->hasFile('aadhaar_back')) {
            $name = time() . '_aadhaar_back_' . $request->file('aadhaar_back')->getClientOriginalName();
            $request->file('aadhaar_back')->move($uploadPath, $name);
            $aadhaarBack = 'uploads/kyc/' . $name;
        }

        if ($request->hasFile('pan_card_image')) {
            $name = time() . '_pan_' . $request->file('pan_card_image')->getClientOriginalName();
            $request->file('pan_card_image')->move($uploadPath, $name);
            $panImage = 'uploads/kyc/' . $name;
        }

        if ($request->hasFile('user_photo')) {
            $name = time() . '_photo_' . $request->file('user_photo')->getClientOriginalName();
            $request->file('user_photo')->move($uploadPath, $name);
            $photo = 'uploads/kyc/' . $name;
        }

        $applicationId = DB::table('paylater_applications')->insertGetId([
            'user_id'         => $userId,
            'full_name'       => $request->full_name,
            'email'           => $request->email ?? auth()->user()->email,
            'phone'           => $request->phone,
            'dob'             => $request->dob,

            'address'         => $request->address,
            'city'            => $request->city,
            'state'           => $request->state,
            'pincode'         => $request->pincode,

            'aadhaar_number'  => $request->aadhaar_number,
            'pan_number'      => strtoupper($request->pan_number),

            'aadhaar_front'   => $aadhaarFront,
            'aadhaar_back'    => $aadhaarBack,
            'pan_card_image'  => $panImage,
            'user_photo'      => $photo,

            'status'          => 'pending',
            'approved_limit'  => 0,

            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Application submitted successfully',
            'data' => [
                'application_id' => $applicationId,
                'status' => 'pending'
            ]
        ]);
    }
}