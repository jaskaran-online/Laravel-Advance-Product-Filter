<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Models\Product;

    class ProductController extends Controller
    {
        public function index(Request $request = null)
        {
            $productBrand = Product::select('product_brand')->groupBy('product_brand')->get();
            $productStorage = Product::select('product_storage')->groupBy('product_storage')->get();
            $ram = Product::select('product_ram')->groupBy('product_ram')->get();
            return view('products', ['rams' => $ram, 'brands' => $productBrand, 'storages' => $productStorage]);
        }

        public function filter(Request $request)
        {

            $productObj = Product::Query();
            if ($request->has('action')) {
                $productObj->where('product_status', '=', '1');
                if ($request->has('minimum_price') and $request->has('maximum_price')) {
                    $productObj->whereBetween('product_price', [$request->minimum_price,$request->maximum_price]);
                }
                if ($request->has('brand')) {
                    $productObj->whereIn('product_brand', $request->brand);
                }
                if ($request->has('ram')) {
                    $productObj->whereIn('product_ram', $request->ram);
                }
                if ($request->has('storage')) {
                    $productObj->whereIn('product_storage', $request->storage);
                }
                return response()->json(['products' => $productObj->get()]);
            }
        }
    }
