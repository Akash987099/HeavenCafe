<?php

namespace App\Http\Controllers;

use App\Models\PartyMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PartyMasterController extends Controller
{
    public function index(Request $request)
    {
        $parties = PartyMaster::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->boolean('status')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%"));
            })
            ->latest('id')
            ->paginate((int) $request->input('per_page', 20));

        if ($request->expectsJson()) {
            return response()->json($parties);
        }

        $editingParty = $request->filled('edit') ? PartyMaster::findOrFail($request->integer('edit')) : null;

        return view('party-master.index', compact('parties', 'editingParty'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['image'] = $this->storeImage($request);
        $party = PartyMaster::create($data + [
            'slug' => $this->uniqueSlug($data['name']),
            'created_by' => auth('admin')->id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Party created successfully.', 'data' => $party], 201);
        }

        return redirect()->route('party_master.index')->with('success', 'Party type created successfully.');
    }

    public function show(PartyMaster $partyMaster)
    {
        return response()->json(['data' => $partyMaster]);
    }

    public function update(Request $request, PartyMaster $partyMaster)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('image')) {
            if ($partyMaster->image && File::exists(public_path($partyMaster->image))) {
                File::delete(public_path($partyMaster->image));
            }
            $data['image'] = $this->storeImage($request);
        }
        $partyMaster->update($data + ['slug' => $this->uniqueSlug($data['name'], $partyMaster->id)]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Party updated successfully.', 'data' => $partyMaster->fresh()]);
        }

        return redirect()->route('party_master.index')->with('success', 'Party type updated successfully.');
    }

    public function destroy(Request $request, PartyMaster $partyMaster)
    {
        $partyMaster->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Party deleted successfully.']);
        }

        return redirect()->route('party_master.index')->with('success', 'Party type deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'status' => ['sometimes', 'boolean'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $exceptId = null): string
    {
        $base = Str::slug($name) ?: 'party';
        $slug = $base;
        $suffix = 2;
        while (PartyMaster::query()->where('slug', $slug)->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        // Do not use `public/party-master`: it conflicts with the /party-master web route
        // when the application runs through PHP's built-in development server.
        $directory = public_path('party-master-images');
        File::ensureDirectoryExists($directory);
        $image = $request->file('image');
        $filename = uniqid('party_', true) . '.' . $image->extension();
        $image->move($directory, $filename);

        return 'party-master-images/' . $filename;
    }
}
