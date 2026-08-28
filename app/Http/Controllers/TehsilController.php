<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Tehsil;

class TehsilController extends Controller
{
    protected $district;
    protected $tehsil;

    public function __construct()
    {
        $this->district = new District();
        $this->tehsil = new Tehsil();
    }

    public function index()
    {
        $tehsil = $this->tehsil->join('districts', 'tehsils.district_id', 'districts.id')->select('tehsils.*', 'districts.name as district_name')->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('tehsil.index', compact('tehsil'));
    }

    public function add()
    {
        $district = $this->district->all();
        return view('tehsil.add', compact('district'));
    }

    public function export()
    {
        $tehsils = $this->tehsil
            ->join('districts', 'tehsils.district_id', 'districts.id')
            ->select('tehsils.name', 'districts.name as district_name')
            ->orderBy('tehsils.id', 'desc')
            ->get();

        $fileName = 'tehsils_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($tehsils) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'District', 'Name']);

            foreach ($tehsils as $index => $tehsil) {
                fputcsv($file, [$index + 1, $tehsil->district_name, $tehsil->name]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = [
            'district_id' => $request->district,
            'name'     => $request->name,
        ];
        $this->tehsil->create($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function edit($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Id not found');
        }

        $tehsil = $this->tehsil->find($id);
        if (!$tehsil) {
            return redirect()->back()->with('error', 'no record found!');
        }
        $district = $this->district->all();
        return view('tehsil.edit', compact('district', 'tehsil'));
    }

    public function update(Request $request){
        $request->validate([
            'id'    => 'required|exists:tehsils,id',
            'district'    => 'required|exists:districts,id',
            'name'  => 'required|string|max:255',
        ]);

        $tehsil = $this->tehsil->find($request->id);

        if (!$tehsil) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $tehsil->name = $request->name;
        $tehsil->district_id = $request->district;

        if ($tehsil->save()) {
            return redirect()->back()->with('success', 'Category updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }
}
