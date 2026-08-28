<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{
    protected $table;

    public function __construct()
    {
        $this->table = new Table();
    }

    public function index(Request $request)
    {
        $tables = $this->table->paginate(config('constants.pagination_limit'));
        return view('tables.index', compact('tables'));
    }

    public function add()
    {
        return view('tables.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'table_no' => 'required|integer|unique:tables,table_no',
            'seat' => 'required|integer',
        ]);

        $this->table->create([
            'table_no' => $request->table_no,
            'seat' => $request->seat,
        ]);

        return redirect()->back()->with('success', 'Table created successfully.');
    }

    public function edit($id)
    {
        $table = $this->table->findOrFail($id);
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:tables,id',
            'table_no' => 'required|integer|unique:tables,table_no,' . $request->id,
            'seat' => 'required|integer',
        ]);

        $table = $this->table->findOrFail($request->id);
        $table->update([
            'table_no' => $request->table_no,
            'seat' => $request->seat,
        ]);

        return redirect()->back()->with('success', 'Table updated successfully.');
    }
}
