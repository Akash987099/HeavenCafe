<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeValueController extends Controller
{
    protected $attribute;
    protected $attribute_value;

    public function __construct()
    {
        $this->attribute = new Attribute();
        $this->attribute_value = new AttributeValue();
    }

    public function index()
    {
        $attribute_values = $this->attribute_value->join('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')
                                ->select('attribute_values.*', 'attributes.name as attribute_name')
                                ->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('attribute_value.index', compact('attribute_values'));
    }

    public function add()
    {
        $attributes = $this->attribute->all();
        return view('attribute_value.add', compact('attributes'));
    }

    public function export()
    {
        $attributeValues = $this->attribute_value
            ->join('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')
            ->select('attributes.name as attribute_name', 'attribute_values.value')
            ->orderBy('attribute_values.id', 'desc')
            ->get();

        $fileName = 'attribute_values_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($attributeValues) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Attribute', 'Value']);

            foreach ($attributeValues as $index => $attributeValue) {
                fputcsv($file, [$index + 1, $attributeValue->attribute_name, $attributeValue->value]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'attribute_id' => 'required',
        ]);
        $attribute_value = $this->attribute_value;
        
        $attribute_value->value = $request->name;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->save();
        return redirect()->back()->with('success', 'Successfully!');
    }

    public function edit($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $attribute_value = $this->attribute_value->find($id);

        if (!$attribute_value) {
            return redirect()->back()->with('error', 'Record not found!');
        }
        $attributes = $this->attribute->all();
        return view('attribute_value.edit', compact('attribute_value', 'attributes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:attribute_values,id',
            'name' => 'required',
            'attribute_id' => 'required',
        ]);
        $attribute_value = $this->attribute_value->find($request->id);
        $attribute_value->value = $request->name;
        $attribute_value->attribute_id = $request->attribute_id;
        if ($attribute_value->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }
        return redirect()->back()->with('error', 'Update failed!');

    }
}
