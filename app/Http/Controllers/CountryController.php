<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Countries;

class CountryController extends Controller
{
    protected $country;

    public function __construct()
    {
        $this->country = new Countries();
    }
    public function index()
    {
        $countries = $this->country->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('country.index', compact('countries'));
    }

    public function add()
    {
        return view('country.add');
    }

    public function export()
    {
        $countries = $this->country->orderBy('id', 'desc')->get([
            'country_name',
            'country_code',
            'country_short',
            'currency_name',
            'currency_code',
            'currency_symbol',
            'currency_rate',
            'status',
        ]);

        $fileName = 'countries_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($countries) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Code', 'Currency SN', 'Currency', 'CCO', 'Symbol', 'Rate', 'Status']);

            foreach ($countries as $index => $country) {
                fputcsv($file, [
                    $index + 1,
                    $country->country_name,
                    $country->country_code,
                    $country->country_short,
                    $country->currency_name,
                    $country->currency_code,
                    $country->currency_symbol,
                    $country->currency_rate,
                    (int) $country->status === 1 ? 'Active' : 'Inactive',
                ]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|unique:countries,country_name',
            'code'           => 'required',
            'short_name'     => 'required',
            'currency'       => 'required',
            'currency_code'  => 'required',
            'symbol'         => 'required',
            'price'          => 'required|numeric',
        ], [
            'name.required'  => 'Country name is required.',
            'name.unique'    => 'This country name already exists.',
            'code.required'  => 'Country code is required.',
            'short_name.required' => 'Country short name is required.',
            'currency.required' => 'Currency name is required.',
            'currency_code.required' => 'Currency code is required.',
            'symbol.required' => 'Currency symbol is required.',
            'price.required'  => 'Currency rate is required.',
            'price.numeric'   => 'Currency rate must be a number.',
        ]);

        $data = [
            'country_name' => $request->name,
            'country_code' => $request->code,
            'country_short' => $request->short_name,
            'currency_name' => $request->currency,
            'currency_code' => $request->currency_code,
            'currency_symbol' => $request->symbol,
            'currency_rate' => $request->price,
            'status'       => 1
        ];

        $this->country->create($data);

        return redirect()->back()->with('success', 'Successfully.');
    }

    public function edit($id)
    {
        $country = $this->country->find($id);
        if (!$country) {
            return redirect()->back()->with('error', 'No record found!');
        }
        return view('country.edit', compact('country'));
    }

    public function update(Request $request)
    {
        $country = $this->country->find($request->id);

        if (!$country) {
            return redirect()->back()->with('error', 'Country not found.');
        }

        $request->validate([
            'name'           => 'required|unique:countries,country_name,' . $country->id,
            'code'           => 'required',
            'short_name'     => 'required',
            'currency'       => 'required',
            'currency_code'  => 'required',
            'symbol'         => 'required',
            'price'          => 'required|numeric',
        ], [
            'name.required'  => 'Country name is required.',
            'name.unique'    => 'This country name already exists.',
            'code.required'  => 'Country code is required.',
            'short_name.required' => 'Country short name is required.',
            'currency.required' => 'Currency name is required.',
            'currency_code.required' => 'Currency code is required.',
            'symbol.required' => 'Currency symbol is required.',
            'price.required'  => 'Currency rate is required.',
            'price.numeric'   => 'Currency rate must be a number.',
        ]);

        $data = [
            'country_name'    => $request->name,
            'country_code'    => $request->code,
            'country_short'   => $request->short_name,
            'currency_name'   => $request->currency,
            'currency_code'   => $request->currency_code,
            'currency_symbol' => $request->symbol,
            'currency_rate'   => $request->price,
            'status'          => 1,
        ];

        $country->update($data);

        return redirect()->back()->with('success', 'Country updated successfully!');
    }
    
}
