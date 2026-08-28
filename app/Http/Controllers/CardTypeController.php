<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardType;

class CardTypeController extends Controller
{
    protected $cardType;

    public function __construct(CardType $cardType)
    {
        $this->cardType = $cardType;
    }

    public function index()
    {
        $cardTypes = $this->cardType->paginate(config('constants.pagination_limit'));
        return view('card_type.index', compact('cardTypes'));
    }

    public function add()
    {
        return view('card_type.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
            'description' => 'required'
        ]);

        $data = [];
        $data['name'] = $request->name;
        $data['description'] = $request->description;

        if ($request->hasFile('image')) {

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('card_types'), $imageName);

            $data['image'] = 'card_types/' . $imageName;
        }

        $this->cardType->create($data);

        return redirect()->route('card_type.index')->with('success', 'Card Type created successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:card_types,id',
            'status' => 'required|in:0,1',
        ]);

        $cardType = $this->cardType->find($request->id);

        if (!$cardType) {
            return response()->json(['success' => false, 'message' => 'Card Type not found.']);
        }

        $cardType->status = $request->status;
        $cardType->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function edit($id)
    {
        $cardType = $this->cardType->find($id);

        if (!$cardType) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('card_type.edit', compact('cardType'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:card_types,id',
            'name' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image',
        ]);

        $cardType = $this->cardType->find($request->id);

        if (!$cardType) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $cardType->name = $request->name;
        $cardType->description = $request->description;

        if ($request->hasFile('image')) {

            if ($cardType->image && file_exists(public_path($cardType->image))) {
                unlink(public_path($cardType->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('card_types'), $imageName);

            $cardType->image = 'card_types/' . $imageName;
        }

        if ($cardType->save()) {
            return redirect()->back()->with('success', 'Card Type updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }
}
