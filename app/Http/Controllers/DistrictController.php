<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\State;
use App\Models\Countries;

class DistrictController extends Controller
{
    protected $disctrict;
    protected $state;
    protected $country;

    public function __construct()
    {
        $this->disctrict = new District();
        $this->state     = new State();
        $this->country   = new Countries();
    }

    public function index()
    {
        $district = $this->disctrict->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('district.index', compact('district'));
    }
    public function add()
    {
        $country = $this->country->all();
        $state = $this->state->where('country_id', '1')->get();
        return view('district.add', compact('country', 'state'));
    }

    public function export()
    {
        $districts = $this->disctrict->orderBy('id', 'desc')->get(['name']);
        $fileName = 'districts_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($districts) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name']);

            foreach ($districts as $index => $district) {
                fputcsv($file, [$index + 1, $district->name]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
    public function store(Request $request)
    {
        $data = [
            'state_id' => $request->state,
            'name'     => $request->name,
        ];
        $this->disctrict->create($data);
        return redirect()->back()->with('success', 'Success!');
    }
    public function edit($id)
    {
        $disctrict = $this->disctrict->find($id);
        $country = $this->country->all();
        $state = $this->state->where('country_id', '1')->get();
        if ($disctrict) {
            return view('district.edit', compact('country', 'state', 'disctrict'));
        }
        return redirect()->back()->with('error', 'no record found!');
    }
    public function update(Request $request)
    {
        $district = $this->disctrict->find($request->id);
        $data = [
            'state_id' => $request->state,
            'name'     => $request->name,
        ];
        $district->update($data);
        return redirect()->back()->with('success', 'Success!');
    }
    public function delete($id)
    {
        try {
            $district = $this->disctrict->findOrFail($id);
            $district->delete();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'exceptionError', 'error' => $e->getMessage()]);
        }
    }
}
