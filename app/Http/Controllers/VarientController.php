<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varient;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\VarientValue;
use Illuminate\Support\Facades\DB;

class VarientController extends Controller
{
    protected $varient;
    protected $attribute;
    protected $attribute_value;
    protected $product;
    protected $varient_value;

    public function __construct()
    {
        $this->varient = new Varient();
        $this->attribute = new Attribute();
        $this->attribute_value = new AttributeValue();
        $this->product = new Product();
        $this->varient_value = new VarientValue();
    }

    public function add($id)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID not found!');
        }

        $varient = $this->varient->where('product_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(config('constants.pagination_limit'));
        $varient->getCollection()->transform(function ($variant) {
            $variant->attribute_summary = $this->varient_value
                ->leftJoin('attributes', 'attributes.id', '=', 'product_variant_values.attribute_id')
                ->leftJoin('attribute_values', 'attribute_values.id', '=', 'product_variant_values.attribute_value_id')
                ->where('product_variant_values.variant_id', $variant->id)
                ->select('attributes.name as attribute_name', 'attribute_values.value as attribute_value')
                ->get()
                ->map(function ($item) {
                    return trim(($item->attribute_name ?? 'Attribute') . ': ' . ($item->attribute_value ?? '-'));
                })
                ->implode(', ');

            return $variant;
        });
        $attribute = $this->attribute->orderBy('id', 'desc')->get();
        $attribute_value = $this->attribute_value->orderBy('id', 'desc')->get();
        $product = $this->product->find($id);

        return view('varient.add', compact('varient', 'attribute', 'attribute_value', 'product'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'sku' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|integer|exists:attribute_values,id',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $variant = new Varient();

            $variant->product_id = $validated['product_id'];
            $variant->sku = $validated['sku'] ?? null;
            $variant->price = $validated['price'];
            $variant->stock = $validated['stock'];

            if ($request->hasFile('image')) {
                $image = time() . '.' . $request->image->extension();
                $request->image->move(public_path('variant'), $image);
                $variant->image = $image;
            }

            $variant->save();

            $attributes = $request->input('attributes', []);
            foreach ($attributes as $attribute_id => $value_id) {
                if (!empty($value_id)) {
                    $this->varient_value->create([
                        'variant_id' => $variant->id,
                        'attribute_id' => $attribute_id,
                        'attribute_value_id' => $value_id
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Variant Added Successfully');
    }

    public function delete($id)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'Variant ID not found!');
        }

        $variant = $this->varient->find($id);

        if (!$variant) {
            return redirect()->back()->with('error', 'Variant record not found!');
        }

        DB::transaction(function () use ($variant) {
            if (!empty($variant->image)) {
                $imagePath = public_path('variant/' . $variant->image);

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $this->varient_value->where('variant_id', $variant->id)->delete();
            $variant->delete();
        });

        return redirect()->back()->with('success', 'Variant deleted successfully');
    }
}
