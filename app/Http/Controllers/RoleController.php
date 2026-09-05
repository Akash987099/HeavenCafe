<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderByDesc('id')->paginate(config('constants.pagination_limit'));

        return view('role.index', compact('roles'));
    }

    public function add()
    {
        return view('role.add');
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'role_name' => 'required|string|max:100|unique:roles,role_name',
            'status' => 'required|boolean',
        ]);

        Role::create($data);

        return redirect()->route('role.index')->with('success', 'Role added successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('role.edit', compact('role'));
    }

    public function update(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $data = $request->validate([
            'id' => 'required|integer',
            'role_name' => 'required|string|max:100|unique:roles,role_name,' . $role->id,
            'status' => 'required|boolean',
        ]);

        $role->update($data);

        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    public function export()
    {
        $roles = Role::orderByDesc('id')->get(['role_name', 'status']);

        return response()->streamDownload(function () use ($roles) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Role Name', 'Status']);

            foreach ($roles as $index => $role) {
                fputcsv($file, [$index + 1, $role->role_name, $role->status ? 'Active' : 'Inactive']);
            }

            fclose($file);
        }, 'roles_' . now()->format('Y_m_d_H_i_s') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
