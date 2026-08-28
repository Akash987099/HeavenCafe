<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\village;
use App\Models\Block;

class VillageController extends Controller
{
    protected $block;
    protected $village;

    public function __construct()
    {
        $this->block = new Block();
        $this->village = new village();
    }

    public function index()
    {
        $villages = $this->village
            ->join('blocks', 'villages.block_id', '=', 'blocks.id')
            ->select('villages.*', 'blocks.name as block_name')
            ->orderBy('blocks.id', 'desc')
            ->paginate(config('constants.pagination_limit'));
            // dd($villages);

        return view('village.index', compact('villages'));
    }

    public function add()
    {
        $blocks = $this->block->all();
        return view('village.add', compact('blocks'));
    }

    public function export()
    {
        $villages = $this->village
            ->join('blocks', 'villages.block_id', '=', 'blocks.id')
            ->select('villages.name', 'blocks.name as block_name')
            ->orderBy('villages.id', 'desc')
            ->get();

        $fileName = 'villages_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($villages) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Block', 'Name']);

            foreach ($villages as $index => $village) {
                fputcsv($file, [$index + 1, $village->block_name, $village->name]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $village = $this->village;
        $village->name = $request->name;
        $village->block_id = $request->block;
        $save = $village->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function edit($id)
    {
        $village = $this->village->find($id);
        $blocks = $this->block->all();
        return view('village.edit', compact('blocks', 'village'));
    }

    public function update(Request $request){
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $village = $this->village->find($request->id);
        $village->name = $request->name;
        $village->block_id = $request->block;
        $save = $village->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }
}
