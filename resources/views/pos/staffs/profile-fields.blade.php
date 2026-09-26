@php($editingStaff = $staff ?? null)
<div class="mt-7 border-t border-slate-200 pt-6">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-[#128C7E]">
            <i class="fas fa-id-card"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-800">Employment &amp; Personal Details</h3>
            <p class="mt-0.5 text-xs text-slate-400">Optional information for the staff profile</p>
        </div>
    </div>
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                @if ($editingStaff?->staff_image)
                    <img src="{{ asset($editingStaff->staff_image) }}" alt="{{ $editingStaff->name }}"
                        class="h-20 w-20 rounded-xl object-cover ring-1 ring-slate-200">
                @else
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-2xl text-[#128C7E]">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Staff Photo</label>
                    <input type="file" name="staff_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#128C7E] hover:file:bg-emerald-100">
                    <p class="mt-2 text-xs text-slate-400">JPG, PNG or WEBP. Maximum file size: 2 MB.</p>
                </div>
            </div>
        </div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Date of Joining</label><input type="date"
                name="date_of_joining"
                value="{{ old('date_of_joining', $editingStaff?->date_of_joining?->format('Y-m-d')) }}"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Designation</label><select name="designation"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]">
                <option value="">Select designation</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->role_name }}"
                        {{ old('designation', $editingStaff?->designation) === $role->role_name ? 'selected' : '' }}>
                        {{ $role->role_name }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Monthly Salary</label><input type="number"
                name="salary" value="{{ old('salary', $editingStaff?->salary) }}" min="0" step="0.01"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Date of Birth</label><input type="date"
                name="date_of_birth" value="{{ old('date_of_birth', $editingStaff?->date_of_birth?->format('Y-m-d')) }}"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Gender</label><select name="gender"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]">
                <option value="">Select gender</option>
                @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('gender', $editingStaff?->gender) === $value ? 'selected' : '' }}>{{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Emergency Contact Name</label><input
                type="text" name="emergency_contact_name"
                value="{{ old('emergency_contact_name', $editingStaff?->emergency_contact_name) }}"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Emergency Contact Mobile</label><input
                type="text" name="emergency_contact_mobile"
                value="{{ old('emergency_contact_mobile', $editingStaff?->emergency_contact_mobile) }}" maxlength="10"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Bank Name</label><input type="text"
                name="bank_name" value="{{ old('bank_name', $editingStaff?->bank_name) }}"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Bank Account Number</label><input
                type="text" name="bank_account_number"
                value="{{ old('bank_account_number', $editingStaff?->bank_account_number) }}"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">IFSC Code</label><input type="text"
                name="bank_ifsc_code" value="{{ old('bank_ifsc_code', $editingStaff?->bank_ifsc_code) }}"
                class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"></div>
        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Documents</label><input type="file"
                name="documents[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]">
            <p class="mt-1 text-xs text-slate-400">Optional. Up to 10 files, 10 MB each.</p>
            @if ($editingStaff && !empty($editingStaff->documents))
                <div class="mt-2">
                    @foreach ($editingStaff->documents as $document)
                        <a href="{{ asset($document) }}" target="_blank"
                            class="mr-2 text-xs text-[#128C7E] underline">Document {{ $loop->iteration }}</a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="md:col-span-2"><label class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
            <textarea name="address" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]">{{ old('address', $editingStaff?->address) }}</textarea>
        </div>
    </div>
</div>
