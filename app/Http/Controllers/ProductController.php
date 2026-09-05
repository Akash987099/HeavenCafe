<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Ddsire_shoe;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Client;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Milon\Barcode\DNS1D;
use App\Models\Gallery;
use App\Models\Summer;
use App\Models\Type;
use App\Models\Varient;
use App\Models\VarientValue;
use App\Models\Plateform;
use App\Models\Tax;
use App\Models\ProductPartner;
use App\Models\ChildCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $category;
    protected $sub_category;
    protected $child_category;
    protected $brand;
    protected $discount;
    protected $product;
    protected $gallery;
    protected $summer;
    protected $type;
    protected $client;
    protected $ddsireShoe;
    protected $attribute;
    protected $attributeValue;
    protected $varient;
    protected $varientValue;
    protected $plateform;
    protected $ProductPartner;
    protected $tax;

    public function __construct()
    {
        $this->category = new Category();
        $this->sub_category = new SubCategory();
        $this->child_category = new ChildCategory();
        $this->brand = new Brand();
        $this->discount = new Discount();
        $this->product = new Product();
        $this->gallery = new Gallery();
        $this->summer = new Summer();
        $this->type = new Type();
        $this->client = new Client();
        $this->ddsireShoe = new Ddsire_shoe();
        $this->attribute = new Attribute();
        $this->attributeValue = new AttributeValue();
        $this->varient = new Varient();
        $this->varientValue = new VarientValue();
        $this->plateform = new Plateform();
        $this->ProductPartner = new ProductPartner();
        $this->tax     = new Tax();
    }


    public function index()
    {
        $summer = $this->summer->all();
        $tax = $this->tax->all();
        $brands = $this->brand->orderBy('name')->get();
        $clients = $this->client->where('status', 1)->orderBy('name')->get();
        $keyword = trim((string) request('q', ''));

        $productsQuery = $this->product->orderBy('id', 'desc');

        if ($keyword !== '') {
            $productsQuery->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku_product_id', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku_code', 'LIKE', "%{$keyword}%")
                    ->orWhere('tags', 'LIKE', "%{$keyword}%");
            });
        }

        $products = $productsQuery
            ->paginate(config('constants.pagination_limit'))
            ->appends(['q' => $keyword]);

        return view('product.index', compact('products', 'summer', 'brands', 'clients', 'tax'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->get('q', ''));

        if ($keyword === '') {
            return response()->json([
                'status' => 'success',
                'data' => [],
            ]);
        }

        $products = $this->product
            ->select('id', 'name', 'sku_product_id', 'sku_code')
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku_product_id', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku_code', 'LIKE', "%{$keyword}%")
                    ->orWhere('tags', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku_product_id' => $product->sku_product_id,
                    'sku_code' => $product->sku_code,
                    'edit_url' => route('product.edit', $product->id),
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function add()
    {
        $category = $this->category->all();
        $sub_category = $this->sub_category->all();
        $child_category = $this->child_category->all();
        $brand = $this->brand->all();
        $discount = $this->discount->all();
        $type = $this->type->all();
        $tax = $this->tax->all();
        return view('product.add', compact('category', 'sub_category', 'child_category', 'brand', 'discount', 'type', 'tax'));
    }

    public function import(Request $request)
    {
        return redirect()->route('product.index')->with('info', 'File import is not configured right now.');
    }

    public function sampleDownload()
    {
        return redirect()->route('product.index')->with('info', 'Sample file is not configured yet.');
    }

    public function export()
    {
        $products = $this->product->orderBy('id', 'desc')->get([
            'sku_product_id',
            'name',
            'sku_code',
            'hsn_code',
            'status',
            'in_stock',
            'stock',
            'price',
            'ac_price'
        ]);

        $fileName = 'products_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Product Id', 'Name', 'SKU', 'HSN', 'Status', 'In Stock', 'Stock', 'Price', 'Actual Price']);

            foreach ($products as $index => $product) {
                fputcsv($file, [
                    $index + 1,
                    $product->sku_product_id,
                    $product->name,
                    $product->sku_code,
                    $product->hsn_code,
                    $product->status,
                    (int) $product->in_stock === 1 ? 'Stock' : 'Out of Stock',
                    $product->stock,
                    $product->price,
                    $product->ac_price,
                ]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function generateUniqueAwb()
    {
        do {
            $awb = random_int(10000000, 99999999);
        } while (Product::where('sku_product_id', $awb)->exists());

        return $awb;
    }

    private function generateBarcode($awb)
    {
        $dns1d = new DNS1D();

        $barcodeBase64 = $dns1d->getBarcodePNG($awb, 'C128', 2, 60);
        $barcodeImage = imagecreatefromstring(base64_decode($barcodeBase64));

        $barcodeWidth  = imagesx($barcodeImage);
        $barcodeHeight = imagesy($barcodeImage);

        $finalHeight = $barcodeHeight + 30;
        $finalImage  = imagecreatetruecolor($barcodeWidth, $finalHeight);

        $white = imagecolorallocate($finalImage, 255, 255, 255);
        $black = imagecolorallocate($finalImage, 0, 0, 0);

        imagefill($finalImage, 0, 0, $white);

        imagecopy($finalImage, $barcodeImage, 0, 0, 0, 0, $barcodeWidth, $barcodeHeight);

        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($awb);
        $textX = ($barcodeWidth - $textWidth) / 2;
        $textY = $barcodeHeight + 5;

        imagestring($finalImage, $fontSize, $textX, $textY, $awb, $black);

        ob_start();
        imagepng($finalImage);
        $imageData = ob_get_clean();

        imagedestroy($barcodeImage);
        imagedestroy($finalImage);

        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    public function save(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
        ]);

        $awbNumber = $this->generateUniqueAwb();
        $barcodeBase64 = $this->generateBarcode($awbNumber);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('product'), $imageName);

        $product = $this->product;
        $product->name = $request->name;
        $product->sku_product_id = $awbNumber;
        $product->brand_name = $request->brand_name;
        $product->status = $request->status;
        $product->price = $request->price;
        $product->slug = $request->slug;
        $product->ac_price = $request->ac_price;
        $product->sku_code = $request->sku_code;
        $product->hsn_code = $request->hsn_code;
        $product->tags = $request->tags;
        $product->meta_tag = $request->meta_tag;
        $product->category = $request->category;
        $product->sub_category = $request->sub_category;
        $product->child_category = $request->child_category;
        $product->discount = $request->discount;
        $product->brands = $request->brand;
        $product->type = $request->type;
        $product->type_value = $request->type_value;
        $product->description = $request->description;
        $product->tax = $request->tax;
        $product->is_store = $request->assign_store;
        $product->store_qty = $request->store_qty;
        $product->short_description = $request->short_description;
        $product->image = 'product/' . $imageName;
        $product->barcode_base = $barcodeBase64;
        $save = $product->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function edit($id)
    {
        // dd($id);
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $product = $this->product->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $category = $this->category->all();
        $sub_category = $this->sub_category->all();
        $child_category = $this->child_category->all();
        $discount = $this->discount->all();
        $brand = $this->brand->all();
        $type = $this->type->all();
        $tax = $this->tax->all();
        return view('product.edit', compact('product', 'category', 'sub_category', 'child_category', 'discount', 'brand', 'type', 'tax'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:products,id',
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $product = $this->product->find($request->id);

        if (!$product) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $product->name = $request->name;
        $product->status = $request->status;
        $product->price = $request->price;
        $product->brand_name = $request->brand_name;
        $product->ac_price = $request->ac_price;
        $product->sku_code = $request->sku_code;
        $product->hsn_code = $request->hsn_code;
        $product->tags = $request->tags;
        $product->meta_tag = $request->meta_tag;
        $product->category = $request->category;
        $product->sub_category = $request->sub_category;
        $product->child_category = $request->child_category;
        $product->discount = $request->discount;
        $product->short_description = $request->short_description;
        $product->brands = $request->brand;
        $product->type = $request->type;
        $product->type_value = $request->type_value;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->tax = $request->tax;
        $product->is_store = $request->assign_store;
        $product->store_qty = $request->store_qty;

        if ($request->hasFile('image')) {

            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('product'), $imageName);

            $product->image = 'product/' . $imageName;
        }

        if ($product->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }

    public function status(Request $request)
    {
        $status = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID not found'
            ], 400);
        }

        $product = $this->product->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $product->status = $product->status == 'active' ? 'inactive' : 'active';
        $product->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    // Gallery

    public function gallery($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $product = $this->product->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $gallery = $this->gallery->where('product_id', $id)->get();

        return view('product.gallery', compact('product', 'gallery'));
    }

    public function gallery_save(Request $request)
    {
        $request->validate([
            'image'   => 'required',
            'id'      => 'required',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($request->hasFile('image')) {

            foreach ($request->file('image') as $file) {

                $gallery = new Gallery();

                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('gallery'), $imageName);

                $gallery->product_id = $request->id;
                $gallery->image = 'gallery/' . $imageName;
                $gallery->save();
            }

            return redirect()->back()->with('success', 'Images uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No images found!');
    }

    public function gallery_delete($id)
    {

        if (!$id) {
            return response()->json(['status' => 'error', 'message' => "id not found!"]);
        }

        $gallery = $this->gallery->where('id', $id)->first();

        if (!$gallery) {
            return response()->json(['status' => 'error', 'message' => "Record not found!"]);
        }

        if ($gallery->image && file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery = $this->gallery->where('id', $id)->delete();

        if (!$gallery) {
            return response()->json(['status' => 'error', 'message' => "Failed!"]);
        }

        return response()->json(['status' => 'success', 'message' => "Success!"]);
    }

    //plateforms

    public function plateform($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $product = $this->product->find($id);
        $plateform = $this->plateform->get();


        if (!$product) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $ProductPartner = $this->ProductPartner->where('product_id', $id)->get();
        // dd($ProductPartner);

        return view('product.plateform', compact('product', 'plateform', 'ProductPartner'));
    }

    public function plateform_save(Request $request)
    {
        $request->validate([
            'plateform_id' => 'required',
            'product_url' => 'required|url',
            'id'=> 'required',
        ]);

        $productPartner = new ProductPartner();

        $productPartner->product_id = $request->id;
        $productPartner->platform_id = $request->plateform_id;
        $productPartner->product_url = $request->product_url;
        $productPartner->save();

        return redirect()->back()->with('success', 'Plateform added successfully!');
    }

    public function plateform_delete($id)
    {

        if (!$id) {
            return response()->json(['status' => 'error', 'message' => "id not found!"]);
        }

        $productPartner = $this->ProductPartner->where('id', $id)->first();

        if (!$productPartner) {
            return response()->json(['status' => 'error', 'message' => "Record not found!"]);
        }

        $productPartner = $this->ProductPartner->where('id', $id)->delete();

        if (!$productPartner) {
            return response()->json(['status' => 'error', 'message' => "Failed!"]);
        }

        return response()->json(['status' => 'success', 'message' => "Success!"]);
    }

    // Stock

    public function stock($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $product = $this->product->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('product.stock', compact('product'));
    }

    public function stockSave(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $product = $this->product->where('id', $id)->update(['stock' => $request->stock]);

        if (!$product) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return redirect()->back()->with('success', 'Success!');
    }

    public function selectStock(Request $request)
    {
        $status = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID not found'
            ], 400);
        }

        $product = $this->product->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $product->in_stock = $product->in_stock == 1 ? 0 : 1;
        $product->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    public function summerStatus(Request $request)
    {
        $status = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID not found'
            ], 400);
        }

        $product = $this->product->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $product->summer_id = $request->status;
        $product->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:products,id'],
            'field' => ['required', 'in:brand,summer,tax'],
            'value' => ['required', 'integer'],
        ]);

        $products = $this->product->whereIn('id', $validated['ids']);

        if ($validated['field'] === 'brand') {
            $brand = $this->brand->find($validated['value']);

            if (!$brand) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Brand not found'
                ], 404);
            }

            $products->update([
                'brands' => $brand->id,
                'brand_name' => $brand->name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Selected products brand updated successfully.'
            ], 200);
        }

        if ($validated['field'] === 'summer') {

        $summer = $this->summer->find($validated['value']);

        if (!$summer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Summer not found'
            ], 404);
        }

        $products->update([
            'summer_id' => $validated['value'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selected products summer updated successfully.'
        ], 200);
    }

    if ($validated['field'] === 'tax') {

            $products->update([
                'tax' => $validated['value'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Tax Apply successfully.'
            ], 200);
        }

    }

    // Similar

    public function similar($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $products = Product::select('id', 'name')
            ->where('id', '!=', $id)
            ->get();

        $selectedSimilar = $product->similar
            ? json_decode($product->similar, true)
            : [];

        return view('product.similar', compact(
            'id',
            'products',
            'selectedSimilar'
        ));
    }

    public function saveSimilar(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'similar_products' => 'nullable|array',
        ]);

        $product = Product::find($request->product_id);

        $product->similar = !empty($request->similar_products)
            ? json_encode($request->similar_products)
            : null;

        $product->save();

        return redirect()->back()
            ->with('success', 'Similar products updated successfully!');
    }

    public function importApiProducts(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
        ]);

        $client = $this->client->findOrFail($request->client_id);

        if (empty($client->category_id)) {
            return redirect()->route('product.index')->with('error', 'Selected client category is missing.');
        }

        try {
            $response = $this->ddsireShoe->fetchProducts();
        } catch (\Throwable $exception) {
            return redirect()->route('product.index')->with('error', $exception->getMessage());
        }

        if (empty($response['status'])) {
            return redirect()->route('product.index')->with('error', $response['message'] ?? 'Remote API failed.');
        }

        $allProducts = $response['products'] ?? [];
        $totalPages = (int) ($response['total_pages'] ?? 1);

        if ($totalPages > 1) {
            for ($page = 2; $page <= $totalPages; $page++) {
                try {
                    $pageResponse = $this->ddsireShoe->fetchProducts(['page' => $page]);
                    $allProducts = array_merge($allProducts, $pageResponse['products'] ?? []);
                } catch (\Throwable $exception) {
                    break;
                }
            }
        }

        $createdCount = 0;
        $updatedCount = 0;
        $processedProductKeys = [];

        $this->deleteClientProductsForSync($client->id, $allProducts);

        foreach ($allProducts as $remoteProduct) {
            $lookupSlug = $remoteProduct['slug'] ?? null;
            $lookupSku = $remoteProduct['sku'] ?? null;
            $uniqueKey = ($lookupSlug ?: 'no-slug') . '|' . ($lookupSku ?: 'no-sku');

            if (isset($processedProductKeys[$uniqueKey])) {
                continue;
            }
            $processedProductKeys[$uniqueKey] = true;

            DB::transaction(function () use (
                $client,
                $remoteProduct,
                $lookupSlug,
                $lookupSku,
                &$createdCount,
                &$updatedCount
            ) {
                $product = new Product();

                if (!$product->exists) {
                    $product->sku_product_id = $this->generateUniqueAwb();
                    $product->barcode_base = $this->generateBarcode($product->sku_product_id);
                }

                $resolvedCategory = $this->resolveRemoteCategoryData(
                    $remoteProduct['categories'] ?? [],
                    $client->category_id
                );

                $featuredImage = $this->ddsireShoe->downloadImage(
                    $remoteProduct['images']['featured']['original'] ?? null
                );

                $product->client_id = $client->id;
                $product->name = $remoteProduct['name'] ?? 'Unnamed Product';
                $product->brand_name = $remoteProduct['manufacturer'] ?? 'DDesire';
                $product->status = !empty($remoteProduct['inventory']['in_stock']) ? 'active' : 'inactive';
                $product->price = $remoteProduct['prices']['final_price']
                    ?? $remoteProduct['prices']['product_price']
                    ?? 0;
                $product->ac_price = $remoteProduct['prices']['original_price']
                    ?? $remoteProduct['prices']['market_price']
                    ?? 0;
                $product->sku_code = $remoteProduct['sku'] ?? null;
                $product->tags = collect($remoteProduct['categories'] ?? [])->pluck('name')->implode(', ');
                $product->meta_tag = $remoteProduct['seo']['meta_keywords']
                    ?? $remoteProduct['seo']['meta_title']
                    ?? null;
                $product->category = $client->category_id;
                $product->sub_category = $resolvedCategory['sub_category_id'];
                $product->description = $remoteProduct['description'] ?? '';
                $product->short_description = $remoteProduct['description_text']
                    ?? strip_tags($remoteProduct['description'] ?? '');
                $product->slug = $lookupSlug ?: Str::slug($remoteProduct['name'] ?? 'product');
                $product->stock = $remoteProduct['inventory']['quantity'] ?? 0;
                $product->in_stock = !empty($remoteProduct['inventory']['in_stock']) ? 1 : 0;
                $product->product_type = 'single';
                $product->type = 'Size';
                $product->type_value = collect($remoteProduct['product_size'] ?? [])
                    ->filter(fn($size) => trim((string) $size) !== '')
                    ->map(fn($size) => trim((string) $size))
                    ->implode(', ');

                if ($featuredImage) {
                    $product->image = $featuredImage;
                } elseif (!$product->image) {
                    $product->image = '';
                }

                $product->save();

                $this->syncProductGallery($product, $remoteProduct);
                $this->syncProductVariants($product, $remoteProduct, $lookupSku);

                $createdCount++;
            });
        }

        return redirect()->route('product.index')->with(
            'success',
            "API products synced successfully. Created: {$createdCount}, Updated: {$updatedCount}"
        );
    }

    protected function resolveRemoteCategoryData(array $remoteCategories, $clientCategoryId): array
    {
        $categoryId = $clientCategoryId;
        $subCategoryId = null;

        foreach ($remoteCategories as $remoteCategory) {
            $name = trim((string) ($remoteCategory['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            if (!$subCategoryId) {
                $subCategory = $this->sub_category
                    ->where('category_id', $clientCategoryId)
                    ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
                    ->first();

                if ($subCategory) {
                    $subCategoryId = $subCategory->id;
                }
            }
        }

        return [
            'category_id' => $categoryId,
            'sub_category_id' => $subCategoryId,
        ];
    }

    protected function syncProductGallery(Product $product, array $remoteProduct): void
    {
        $remoteImages = [];

        $secondaryImage = $remoteProduct['images']['secondary']['original'] ?? null;
        if (!empty($secondaryImage)) {
            $remoteImages[] = $secondaryImage;
        }

        foreach (($remoteProduct['images']['gallery'] ?? []) as $galleryImage) {
            $imageUrl = $galleryImage['original'] ?? null;

            if (!empty($imageUrl)) {
                $remoteImages[] = $imageUrl;
            }
        }

        $remoteImages = array_values(array_unique(array_filter($remoteImages)));

        $existingGallery = $this->gallery->where('product_id', $product->id)->get();
        foreach ($existingGallery as $item) {
            $this->deleteFileIfExists($item->image);
        }
        $this->gallery->where('product_id', $product->id)->delete();

        foreach ($remoteImages as $imageUrl) {
            $downloadedImage = $this->ddsireShoe->downloadImage($imageUrl, 'gallery');

            if (!$downloadedImage) {
                continue;
            }

            $this->gallery->create([
                'product_id' => $product->id,
                'image' => $downloadedImage,
            ]);
        }
    }

    protected function syncProductVariants(Product $product, array $remoteProduct, ?string $baseSku = null): void
    {
        $sizes = collect($remoteProduct['product_size'] ?? [])
            ->map(fn($size) => trim((string) $size))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($sizes)) {
            $this->deleteProductVariants($product);
            return;
        }

        $this->deleteProductVariants($product);

        $sizeAttribute = $this->attribute->firstOrCreate([
            'name' => 'Size',
        ]);

        $price = $remoteProduct['prices']['final_price']
            ?? $remoteProduct['prices']['product_price']
            ?? 0;
        $stock = $remoteProduct['inventory']['quantity'] ?? 0;
        $now = now();

        foreach ($sizes as $size) {
            $attributeValue = $this->attributeValue->firstOrCreate([
                'attribute_id' => $sizeAttribute->id,
                'value' => (string) $size,
            ]);

            $variantId = DB::table('product_variants')->insertGetId([
                'product_id' => $product->id,
                'sku' => $this->buildVariantSku($baseSku, (string) $size, $product->id),
                'price' => $price,
                'stock' => $stock,
                'image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_variant_values')->insert([
                'variant_id' => $variantId,
                'attribute_id' => $sizeAttribute->id,
                'attribute_value_id' => $attributeValue->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    protected function buildVariantSku(?string $baseSku, string $size, int $productId): string
    {
        $cleanSize = Str::upper(Str::slug($size, ''));

        if (!empty($baseSku)) {
            return $baseSku . '-' . $cleanSize;
        }

        return 'P' . $productId . '-' . $cleanSize;
    }

    protected function deleteProductAssets(Product $product): void
    {
        $this->deleteFileIfExists($product->image);

        $galleryItems = $this->gallery->where('product_id', $product->id)->get();
        foreach ($galleryItems as $galleryItem) {
            $this->deleteFileIfExists($galleryItem->image);
        }

        $aplusIds = DB::table('product_aplus')->where('product_id', $product->id)->pluck('id');
        $aplusImages = DB::table('product_aplus_images')->whereIn('aplus_id', $aplusIds)->get();
        foreach ($aplusImages as $aplusImage) {
            $this->deleteFileIfExists('aplus/' . $aplusImage->image);
        }

        $this->gallery->where('product_id', $product->id)->delete();
        $this->deleteProductVariants($product);
        DB::table('product_aplus_images')->whereIn('aplus_id', $aplusIds)->delete();
        DB::table('product_aplus')->where('product_id', $product->id)->delete();
        DB::table('recommended_products')->where('product_id', $product->id)->orWhere('recommended_product_id', $product->id)->delete();
        DB::table('combo_products')->where('combo_product_id', $product->id)->orWhere('product_id', $product->id)->delete();
        DB::table('reviews')->where('product_id', $product->id)->delete();
        DB::table('wishlists')->where('product_id', $product->id)->delete();
        DB::table('carts')->where('product_id', $product->id)->delete();
        $product->delete();
    }

    protected function deleteClientProductsForSync(int $clientId, array $remoteProducts): void
    {
        $existingClientProducts = $this->product->where('client_id', $clientId)->get();

        foreach ($existingClientProducts as $existingClientProduct) {
            $this->deleteProductAssets($existingClientProduct);
        }

        $remoteSlugs = collect($remoteProducts)->pluck('slug')->filter()->unique()->values();
        $remoteSkus = collect($remoteProducts)->pluck('sku')->filter()->unique()->values();

        if ($remoteSlugs->isEmpty() && $remoteSkus->isEmpty()) {
            return;
        }

        $orphanProducts = $this->product
            ->whereNull('client_id')
            ->where(function ($query) use ($remoteSlugs, $remoteSkus) {
                if ($remoteSlugs->isNotEmpty()) {
                    $query->whereIn('slug', $remoteSlugs->all());
                }

                if ($remoteSkus->isNotEmpty()) {
                    $query->orWhereIn('sku_code', $remoteSkus->all());
                }
            })
            ->get();

        foreach ($orphanProducts as $orphanProduct) {
            $this->deleteProductAssets($orphanProduct);
        }
    }

    protected function deleteProductVariants(Product $product): void
    {
        $variants = DB::table('product_variants')
            ->where('product_id', $product->id)
            ->get();

        foreach ($variants as $variant) {
            $this->deleteVariant($variant);
        }
    }

    protected function deleteVariant($variant): void
    {
        $variantImage = $variant->image;

        if (!empty($variantImage) && !Str::contains($variantImage, '/')) {
            $variantImage = 'variant/' . $variantImage;
        }

        $this->deleteFileIfExists($variantImage);
        DB::table('product_variant_values')->where('variant_id', $variant->id)->delete();
        DB::table('product_variants')->where('id', $variant->id)->delete();
    }

    protected function deleteFileIfExists(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $absolutePath = public_path($path);

        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }

    public function productType(Request $request)
    {
        $status = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID not found'
            ], 400);
        }

        $product = $this->product->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $product->product_type = $status;
        $product->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    public function barcode(){
        $barcodes = $this->product->select('id', 'sku_product_id', 'barcode_base')->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('product.barcodes', compact('barcodes'));
    }

    public function barcode_print(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return redirect()->back()->with('error', 'Please select orders');
        }

        $products = $this->product->whereIn('id', $ids)->get();

        return view('product.print_barcode', compact('products'));
    }
}