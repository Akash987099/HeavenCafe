@php
    $client = $client ?? null;
    $addresses = old('address_type')
        ? collect(old('address_type'))->map(function ($type, $index) {
            return (object) [
                'type' => $type,
                'address' => old('address_line.' . $index),
                'city' => old('address_city.' . $index),
                'state' => old('address_state.' . $index),
                'pincode' => old('address_pincode.' . $index),
            ];
        })
        : collect($client?->addresses ?? [(object) ['type' => '', 'address' => '', 'city' => '', 'state' => '', 'pincode' => '']]);
@endphp

@if ($errors->any())
    <div class="alert alert-danger text-white">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Client Code</label>
        <input type="text" name="client_id" class="form-control"
            value="{{ old('client_id', $client->client_id ?? '') }}" placeholder="Auto generate if blank">
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-control" required>
            <option value="1" {{ old('status', $client->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $client->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $client->name ?? '') }}"
            placeholder="Client name" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Company Name</label>
        <input type="text" name="company_name" class="form-control"
            value="{{ old('company_name', $client->company_name ?? '') }}" placeholder="Company name">
    </div>

    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $client->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $client->email ?? '') }}"
            placeholder="name@example.com" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone ?? '') }}"
            placeholder="Phone number" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">GST Number</label>
        <input type="text" name="gst_number" class="form-control"
            value="{{ old('gst_number', $client->gst_number ?? '') }}" placeholder="GST number">
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ $client ? 'New Password' : 'Password' }}</label>
        <input type="password" name="password" class="form-control"
            placeholder="{{ $client ? 'Leave blank to keep current password' : 'Enter password' }}"
            {{ $client ? '' : 'required' }}>
    </div>

    <div class="col-md-6">
        <label class="form-label">API Key</label>
        <input type="text" name="api_key" class="form-control" value="{{ old('api_key', $client->api_key ?? '') }}"
            placeholder="Auto generate if blank">
    </div>

    <div class="col-md-6">
        <label class="form-label">API Secret</label>
        <input type="text" name="api_secret" class="form-control"
            value="{{ old('api_secret', $client->api_secret ?? '') }}" placeholder="Auto generate if blank">
    </div>

    <div class="col-12 mt-2">
        <h6 class="mb-0">Pickup Address</h6>
    </div>

    <div class="col-md-6">
        <label class="form-label">Pickup Address</label>
        <textarea name="pickup_address" class="form-control" rows="3"
            placeholder="Pickup address">{{ old('pickup_address', $client->pickup_address ?? '') }}</textarea>
    </div>

    <div class="col-md-2">
        <label class="form-label">Pickup City</label>
        <input type="text" name="pickup_city" class="form-control"
            value="{{ old('pickup_city', $client->pickup_city ?? '') }}" placeholder="City">
    </div>

    <div class="col-md-2">
        <label class="form-label">Pickup State</label>
        <input type="text" name="pickup_state" class="form-control"
            value="{{ old('pickup_state', $client->pickup_state ?? '') }}" placeholder="State">
    </div>

    <div class="col-md-2">
        <label class="form-label">Pickup Pincode</label>
        <input type="text" name="pickup_pincode" class="form-control"
            value="{{ old('pickup_pincode', $client->pickup_pincode ?? '') }}" placeholder="Pincode">
    </div>

    <div class="col-12 mt-2">
        <h6 class="mb-0">Return Address</h6>
    </div>

    <div class="col-md-6">
        <label class="form-label">Return Address</label>
        <textarea name="return_address" class="form-control" rows="3"
            placeholder="Return address">{{ old('return_address', $client->return_address ?? '') }}</textarea>
    </div>

    <div class="col-md-2">
        <label class="form-label">Return City</label>
        <input type="text" name="return_city" class="form-control"
            value="{{ old('return_city', $client->return_city ?? '') }}" placeholder="City">
    </div>

    <div class="col-md-2">
        <label class="form-label">Return State</label>
        <input type="text" name="return_state" class="form-control"
            value="{{ old('return_state', $client->return_state ?? '') }}" placeholder="State">
    </div>

    <div class="col-md-2">
        <label class="form-label">Return Pincode</label>
        <input type="text" name="return_pincode" class="form-control"
            value="{{ old('return_pincode', $client->return_pincode ?? '') }}" placeholder="Pincode">
    </div>

    <div class="col-12 mt-2 d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Client Addresses</h6>
        <button type="button" class="btn btn-sm btn-outline-primary mb-0" id="addAddressRow">+ Add Address</button>
    </div>

    <div class="col-12">
        <div id="addressRows">
            @foreach ($addresses as $index => $address)
                <div class="border rounded p-3 mb-3 address-row">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <input type="text" name="address_type[]" class="form-control"
                                value="{{ $address->type }}" placeholder="billing">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Address</label>
                            <input type="text" name="address_line[]" class="form-control"
                                value="{{ $address->address }}" placeholder="Address line">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">City</label>
                            <input type="text" name="address_city[]" class="form-control"
                                value="{{ $address->city }}" placeholder="City">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">State</label>
                            <input type="text" name="address_state[]" class="form-control"
                                value="{{ $address->state }}" placeholder="State">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="address_pincode[]" class="form-control"
                                value="{{ $address->pincode }}" placeholder="Pincode">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<template id="addressRowTemplate">
    <div class="border rounded p-3 mb-3 address-row">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <input type="text" name="address_type[]" class="form-control" placeholder="office">
            </div>
            <div class="col-md-4">
                <label class="form-label">Address</label>
                <input type="text" name="address_line[]" class="form-control" placeholder="Address line">
            </div>
            <div class="col-md-2">
                <label class="form-label">City</label>
                <input type="text" name="address_city[]" class="form-control" placeholder="City">
            </div>
            <div class="col-md-2">
                <label class="form-label">State</label>
                <input type="text" name="address_state[]" class="form-control" placeholder="State">
            </div>
            <div class="col-md-2">
                <label class="form-label">Pincode</label>
                <input type="text" name="address_pincode[]" class="form-control" placeholder="Pincode">
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addButton = document.getElementById('addAddressRow');
        var container = document.getElementById('addressRows');
        var template = document.getElementById('addressRowTemplate');

        if (!addButton || !container || !template) {
            return;
        }

        addButton.addEventListener('click', function() {
            container.insertAdjacentHTML('beforeend', template.innerHTML.trim());
        });
    });
</script>
