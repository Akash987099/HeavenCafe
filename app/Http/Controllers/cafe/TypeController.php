<?php

namespace App\Http\Controllers\cafe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    protected $type;

    public function __construct()
    {
        $this->type = new Type();
    }

    public function index()
    {
        $types = $this->type->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('cafe.type.index', compact('types'));
    }

    public function add()
    {
        return view('cafe.type.add');
    }

    public function export()
    {
        $types = $this->type->orderBy('id', 'desc')->get(['name']);
        $fileName = 'types_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($types) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name']);

            foreach ($types as $index => $type) {
                fputcsv($file, [$index + 1, $type->name]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $type = $this->type;
        $type->name = $request->name;
        $save = $type->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function edit($id)
    {
        // dd($id);
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $type = $this->type->find($id);

        if (!$type) {
            return redirect()->back()->with('error', 'Record not found!');
        }
        return view('cafe.type.edit', compact('type'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:types,id',
            'name'  => 'required|string|max:255',
        ]);

        $type = $this->type->find($request->id);

        if (!$type) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $type->name = $request->name;

        if ($type->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }
}
