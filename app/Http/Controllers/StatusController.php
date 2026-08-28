<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    protected $status;

    public function __construct()
    {
        $this->status = new Status();
    }

    public function index()
    {
        $status = $this->status->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('status.index', compact('status'));
    }

    public function add()
    {
        return view('status.add');
    }

    public function export()
    {
        $statuses = $this->status->orderBy('id', 'desc')->get(['name', 'bg_color', 'text_color']);
        $fileName = 'statuses_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($statuses) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Background Color', 'Text Color']);

            foreach ($statuses as $index => $status) {
                fputcsv($file, [$index + 1, $status->name, $status->bg_color, $status->text_color]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bg_color' => 'required|string|max:50',
            'text_color' => 'required|string|max:50',
        ]);

        $data = [
            'name' => $request->name,
            'bg_color' => $request->bg_color,
            'text_color' => $request->text_color,
        ];

        $this->status->create($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function edit($id)
    {
        $status = $this->status->find($id);
        return view('status.edit', compact('status'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'bg_color' => 'required|string|max:50',
            'text_color' => 'required|string|max:50',
        ]);

        $status = $this->status->find($request->id);

        $data = [
            'name' => $request->name,
            'bg_color' => $request->bg_color,
            'text_color' => $request->text_color,
        ];

        $status->update($data);
        return redirect()->back()->with('success', 'Success!');
    }
}
