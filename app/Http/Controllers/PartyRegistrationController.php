<?php

namespace App\Http\Controllers;

use App\Models\PartyMaster;
use App\Models\PartyRegistration;
use App\Models\Store;
use Illuminate\Http\Request;

class PartyRegistrationController extends Controller
{
    public function adminIndex(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $bookings = PartyRegistration::query()
            ->with([
                'partyMaster:id,name',
                'store:id,name,city',
                'posOrders:id,party_registration_id,order_number,grand_total,payment_status,status,created_at',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('mobile', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('partyMaster', fn ($party) => $party->where('name', 'like', '%' . $search . '%'))
                        ->orWhereHas('store', fn ($store) => $store->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('party-bookings.index', compact('bookings', 'search'));
    }

    public function adminShow(PartyRegistration $booking)
    {
        $booking->load(['partyMaster', 'store', 'posOrders' => fn ($orders) => $orders->with('details')->latest('id')]);

        return view('party-bookings.view', compact('booking'));
    }

    public function index(Request $request)
    {
        $partyTypes = PartyMaster::query()->where('status', true)->orderBy('name')->get();
        $stores = Store::query()->orderBy('name')->get(['id', 'name', 'city', 'address']);
        $selectedStoreId = (int) $request->session()->get('booking_store_id');
        $selectedParty = $request->filled('party')
            ? $partyTypes->firstWhere('slug', $request->query('party'))
            : null;

        return view('party-registration.index', compact('partyTypes', 'stores', 'selectedStoreId', 'selectedParty'));
    }

    public function show(PartyMaster $partyMaster)
    {
        abort_unless($partyMaster->status, 404);

        $partyTypes = PartyMaster::query()->where('status', true)->orderBy('name')->get();
        $selectedParty = $partyMaster;
        $stores = Store::query()->orderBy('name')->get(['id', 'name', 'city', 'address']);
        $selectedStoreId = (int) request()->session()->get('booking_store_id');

        return view('party-registration.index', compact('partyTypes', 'selectedParty', 'stores', 'selectedStoreId'));
    }

    public function selectStore(Request $request)
    {
        $validated = $request->validate(['store_id' => ['required', 'integer', 'exists:store,id']]);
        $request->session()->put('booking_store_id', (int) $validated['store_id']);

        return response()->json(['message' => 'Store selected successfully.']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'party_master_id' => ['required', 'integer', 'exists:party_masters,id'],
            'store_id' => ['required', 'integer', 'exists:store,id'],
            'customer_name' => ['required', 'string', 'max:150'],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:191'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:10000'],
            'venue' => ['nullable', 'string', 'max:500'],
            'requirements' => ['nullable', 'string', 'max:3000'],
        ]);

        abort_unless(PartyMaster::query()->whereKey($validated['party_master_id'])->where('status', true)->exists(), 422);

        $request->session()->put('booking_store_id', (int) $validated['store_id']);
        $registration = PartyRegistration::create($validated + ['status' => 'pending']);

        // Registration stays separate from the food order. Keep its reference in
        // the session so the self-order flow can attach the eventual POS order.
        $request->session()->put('customer_order_store_id', (int) $validated['store_id']);
        $request->session()->put('party_registration_id', $registration->id);
        $request->session()->forget('customer_order_id');

        return redirect()->route('customer-order.menu')
            ->with('success', 'Party registration PR-' . str_pad((string) $registration->id, 5, '0', STR_PAD_LEFT) . ' saved. Now choose food items for your party.');
    }
}
