<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    protected $setting;

    public function __construct()
    {
        $this->setting = new Setting();
    }

    public function index()
    {
        $settings = $this->setting->paginate(config('constants.pagination_limit'));
        return view('setting.index', compact('settings'));
    }

    public function add()
    {
        return view('setting.add');
    }

    public function export()
    {
        $settings = $this->setting->orderBy('id', 'desc')->get(['name', 'slug', 'media_link', 'description']);
        $fileName = 'settings_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($settings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Slug', 'Media Link', 'Description']);

            foreach ($settings as $index => $setting) {
                fputcsv($file, [$index + 1, $setting->name, $setting->slug, $setting->media_link, $setting->description]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required',
        ]);

        $setting = $this->setting;
        $setting->name = $request->name;
        $setting->slug = $request->slug;
        $setting->media_link = $request->media_link;
        $setting->description = $request->description;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('setting'), $imageName);
            $setting->image = 'setting/' . $imageName;
        }

        $save = $setting->save();

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

        $setting = $this->setting->find($id);

        if (!$setting) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:settings,id',
            'name'  => 'required|string|max:255',
            'slug'  => 'required',
        ]);

        $setting = $this->setting->find($request->id);

        if (!$setting) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $setting->name = $request->name;
        $setting->slug = $request->slug;
        $setting->media_link = $request->media_link;
        $setting->description = $request->description;

        if ($request->hasFile('image')) {

            if ($setting->image && file_exists(public_path($setting->image))) {
                unlink(public_path($setting->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('setting'), $imageName);

            $setting->image = 'setting/' . $imageName;
        }

        if ($setting->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }
}
