<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Countries;

class StateController extends Controller
{
    protected $state;
    protected $country;

    public function __construct()
    {
        $this->state = new State();
        $this->country = new Countries();
    }
    public function index()
    {
        $state = $this->state->join('countries', 'state.country_id', 'Countries.id')->orderBy('state.id', 'desc')->paginate(config('constants.pagination_limit'));
        // dd($state);
        return view('state.index', compact('state'));
    }
    public function add()
    {
        $country = $this->country->all();
        return view('state.add', compact('country'));
    }

    public function export()
    {
        $states = $this->state
            ->join('countries', 'state.country_id', 'countries.id')
            ->select('state.name', 'state.short_name', 'countries.country_name')
            ->orderBy('state.id', 'desc')
            ->get();

        $fileName = 'states_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($states) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Country', 'Name', 'Short Name']);

            foreach ($states as $index => $state) {
                fputcsv($file, [$index + 1, $state->country_name, $state->name, $state->short_name]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
    public function store(Request $request)
    {
        $data = [
            'country_id' => $request->country,
            'name'       => $request->name,
            'short_name' => $request->short_name
        ];

        $this->state->create($data);
        return redirect()->back()->with('success', 'Success!');
    }
    public function edit($id)
    {
        $state = $this->state->find($id);
        $country = $this->country->all();
        return view('state.edit', compact('state', 'country'));
    }
    public function update(Request $request)
    {
        $state = $this->state->find($request->id);
        $data = [
            'country_id' => $request->country,
            'name'       => $request->name,
            'short_name' => $request->short_name
        ];

        $state->save($data);
        return redirect()->back()->with('success', 'Success!');
    }
    
}
