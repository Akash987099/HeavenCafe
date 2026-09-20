<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StoreGalleryController extends Controller
{
    public function index()
    {
        $galleries = StoreGallery::query()
            ->with('store:id,name')
            ->orderBy('store_id')
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(config('constants.pagination_limit'));

        return view('store-gallery.index', compact('galleries'));
    }

    public function create()
    {
        $stores = Store::query()->orderBy('name')->get(['id', 'name']);
        return view('store-gallery.form', compact('stores'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['image'] = $this->storeImage($request);
        StoreGallery::create($data);

        return redirect()->route('store_gallery.index')->with('success', 'Gallery image added successfully.');
    }

    public function edit(StoreGallery $gallery)
    {
        $stores = Store::query()->orderBy('name')->get(['id', 'name']);
        return view('store-gallery.form', compact('gallery', 'stores'));
    }

    public function update(Request $request, StoreGallery $gallery)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('image')) {
            $this->removeImage($gallery->image);
            $data['image'] = $this->storeImage($request);
        }

        $gallery->update($data);
        return redirect()->route('store_gallery.index')->with('success', 'Gallery image updated successfully.');
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'store_id' => ['required', 'integer', 'exists:store,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);
    }

    private function storeImage(Request $request): string
    {
        $directory = public_path('store-gallery');
        File::ensureDirectoryExists($directory);
        $file = $request->file('image');
        $name = now()->format('YmdHis') . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $name);

        return 'store-gallery/' . $name;
    }

    private function removeImage(?string $image): void
    {
        if ($image && str_starts_with($image, 'store-gallery/') && File::exists(public_path($image))) {
            File::delete(public_path($image));
        }
    }
}
