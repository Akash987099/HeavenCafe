<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Recommended;

class RecommendedController extends Controller
{
    protected $product;
    protected $recommended;

    public function __construct(Product $product, Recommended $recommended)
    {
        $this->product = $product;
        $this->recommended = $recommended;
    }

    public function index()
    {
        $recommended = $this->recommended->join('products', 'recommended_products.product_id', 'products.id')->select('recommended_products.*', 'products.name')->orderBy('id', 'desc')->paginate(config('pagination_limit'));
        return view('recommended.index', compact('recommended'));
    }

    public function add()
    {
        $products = $this->product->select('id','name')->get();
        return view('recommended.add', compact('products'));
    }

    public function export()
    {
        $recommended = $this->recommended
            ->join('products as source_products', 'recommended_products.product_id', '=', 'source_products.id')
            ->leftJoin('products as target_products', 'recommended_products.recommended_product_id', '=', 'target_products.id')
            ->select(
                'source_products.name as product_name',
                'target_products.name as recommended_product_name'
            )
            ->orderBy('recommended_products.id', 'desc')
            ->get();

        $fileName = 'recommended_products_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($recommended) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Product Name', 'Recommended Product']);

            foreach ($recommended as $index => $item) {
                fputcsv($file, [$index + 1, $item->product_name, $item->recommended_product_name]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'recommended_products' => 'required|array'
        ]);

        $this->recommended
            ->where('product_id', $request->product_id)
            ->delete();

        foreach ($request->recommended_products as $recId) {

            if ($recId == $request->product_id) {
                continue;
            }

            $this->recommended->create([
                'product_id' => $request->product_id,
                'recommended_product_id' => $recId
            ]);
        }

        return redirect()->back()->with('success', 'Recommended products added successfully');
    }
}
