<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientAddress;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    protected $client;
    protected $address;
    protected $category;

    public function __construct()
    {
        $this->client = new Client();
        $this->address = new ClientAddress();
        $this->category = new Category();
    }

    public function index()
    {
        $clients = $this->client
            ->with(['addresses', 'category'])
            ->orderBy('id', 'desc')
            ->paginate(config('constants.pagination_limit'));

        return view('client.index', compact('clients'));
    }

    public function add()
    {
        $categories = $this->category->where('status', 1)->orderBy('name')->get();

        return view('client.add', compact('categories'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|string|max:100|unique:clients,client_id',
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:clients,email',
            'phone' => 'required|string|max:20|unique:clients,phone',
            'company_name' => 'nullable|string|max:150',
            'category_id' => 'required|exists:category,id',
            'gst_number' => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'pickup_address' => 'nullable|string',
            'pickup_city' => 'nullable|string|max:100',
            'pickup_state' => 'nullable|string|max:100',
            'pickup_pincode' => 'nullable|string|max:20',
            'return_address' => 'nullable|string',
            'return_city' => 'nullable|string|max:100',
            'return_state' => 'nullable|string|max:100',
            'return_pincode' => 'nullable|string|max:20',
            'status' => 'required|in:0,1',
            'address_type' => 'nullable|array',
            'address_type.*' => 'nullable|string|max:50',
            'address_line' => 'nullable|array',
            'address_line.*' => 'nullable|string',
            'address_city' => 'nullable|array',
            'address_city.*' => 'nullable|string|max:100',
            'address_state' => 'nullable|array',
            'address_state.*' => 'nullable|string|max:100',
            'address_pincode' => 'nullable|array',
            'address_pincode.*' => 'nullable|string|max:20',
        ]);

        $client = $this->client->create([
            'client_id' => $validated['client_id'] ?? ('CLT-' . strtoupper(Str::random(8))),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company_name' => $validated['company_name'] ?? null,
            'category_id' => $validated['category_id'],
            'gst_number' => $validated['gst_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'api_key' => $validated['api_key'] ?? Str::random(24),
            'api_secret' => $validated['api_secret'] ?? Str::random(40),
            'pickup_address' => $validated['pickup_address'] ?? null,
            'pickup_city' => $validated['pickup_city'] ?? null,
            'pickup_state' => $validated['pickup_state'] ?? null,
            'pickup_pincode' => $validated['pickup_pincode'] ?? null,
            'return_address' => $validated['return_address'] ?? null,
            'return_city' => $validated['return_city'] ?? null,
            'return_state' => $validated['return_state'] ?? null,
            'return_pincode' => $validated['return_pincode'] ?? null,
            'status' => $validated['status'],
        ]);

        $this->syncAddresses($client, $request);

        return redirect()->route('client.index')->with('success', 'Client created successfully.');
    }

    public function edit($id)
    {
        $client = $this->client->with('addresses')->findOrFail($id);
        $categories = $this->category->where('status', 1)->orderBy('name')->get();

        return view('client.edit', compact('client', 'categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:clients,id',
        ]);

        $client = $this->client->findOrFail($request->id);

        $validated = $request->validate([
            'id' => 'required|exists:clients,id',
            'client_id' => 'nullable|string|max:100|unique:clients,client_id,' . $client->id,
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:20|unique:clients,phone,' . $client->id,
            'company_name' => 'nullable|string|max:150',
            'category_id' => 'required|exists:category,id',
            'gst_number' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'pickup_address' => 'nullable|string',
            'pickup_city' => 'nullable|string|max:100',
            'pickup_state' => 'nullable|string|max:100',
            'pickup_pincode' => 'nullable|string|max:20',
            'return_address' => 'nullable|string',
            'return_city' => 'nullable|string|max:100',
            'return_state' => 'nullable|string|max:100',
            'return_pincode' => 'nullable|string|max:20',
            'status' => 'required|in:0,1',
            'address_type' => 'nullable|array',
            'address_type.*' => 'nullable|string|max:50',
            'address_line' => 'nullable|array',
            'address_line.*' => 'nullable|string',
            'address_city' => 'nullable|array',
            'address_city.*' => 'nullable|string|max:100',
            'address_state' => 'nullable|array',
            'address_state.*' => 'nullable|string|max:100',
            'address_pincode' => 'nullable|array',
            'address_pincode.*' => 'nullable|string|max:20',
        ]);

        $payload = [
            'client_id' => $validated['client_id'] ?? $client->client_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company_name' => $validated['company_name'] ?? null,
            'category_id' => $validated['category_id'],
            'gst_number' => $validated['gst_number'] ?? null,
            'api_key' => $validated['api_key'] ?? $client->api_key,
            'api_secret' => $validated['api_secret'] ?? $client->api_secret,
            'pickup_address' => $validated['pickup_address'] ?? null,
            'pickup_city' => $validated['pickup_city'] ?? null,
            'pickup_state' => $validated['pickup_state'] ?? null,
            'pickup_pincode' => $validated['pickup_pincode'] ?? null,
            'return_address' => $validated['return_address'] ?? null,
            'return_city' => $validated['return_city'] ?? null,
            'return_state' => $validated['return_state'] ?? null,
            'return_pincode' => $validated['return_pincode'] ?? null,
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $client->update($payload);
        $this->syncAddresses($client, $request);

        return redirect()->route('client.index')->with('success', 'Client updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:clients,id',
            'status' => 'required|in:0,1',
        ]);

        $client = $this->client->findOrFail($request->id);
        $client->status = $request->status;
        $client->save();

        return response()->json([
            'success' => true,
            'message' => 'Client status updated successfully.',
        ]);
    }

    public function delete($id)
    {
        $client = $this->client->findOrFail($id);
        $this->address->where('client_id', $client->id)->delete();
        $client->delete();

        return redirect()->route('client.index')->with('success', 'Client deleted successfully.');
    }

    protected function syncAddresses(Client $client, Request $request): void
    {
        $this->address->where('client_id', $client->id)->delete();

        $types = $request->input('address_type', []);
        $lines = $request->input('address_line', []);
        $cities = $request->input('address_city', []);
        $states = $request->input('address_state', []);
        $pincodes = $request->input('address_pincode', []);

        foreach ($types as $index => $type) {
            $addressLine = trim((string) ($lines[$index] ?? ''));
            $city = trim((string) ($cities[$index] ?? ''));
            $state = trim((string) ($states[$index] ?? ''));
            $pincode = trim((string) ($pincodes[$index] ?? ''));

            if ($addressLine === '' && $city === '' && $state === '' && $pincode === '') {
                continue;
            }

            $this->address->create([
                'client_id' => $client->id,
                'type' => $type ?: 'other',
                'address' => $addressLine,
                'city' => $city,
                'state' => $state,
                'pincode' => $pincode,
            ]);
        }
    }
}
