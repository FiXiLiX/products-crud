<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Validators\ProductValidator;
use App\Commands\AddNewProduct;
use App\Commands\UpdateProduct;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)->orderBy('updated_at', 'DESC')->get();
        return response()->json($products);
    }

    /**
     * Return image of given product
     *
     * @return \Illuminate\Http\Response
     */
    public function image(Request $request, Product $product)
    {
        if (! Gate::allows('products', $product)) {
            abort(403);
        }
        return response()->file('storage/' . $product->image_url);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(ProductValidator::data());
        $request->validate(ProductValidator::image());
        $product = AddNewProduct::command($request);

        return response()->json($product, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if (! Gate::allows('products', $product)) {
            abort(403);
        }
        $request->validate(ProductValidator::data());
        $updateImage = $request->hasFile('image') && $request->file('image')->isValid();
        if($updateImage) {
            $request->validate(ProductValidator::image());
        }
        $product = UpdateProduct::command($product, $request, $updateImage);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (! Gate::allows('products', $product)) {
            abort(403);
        }
        $product->delete();
        return response()->json(["message" => "success"]);
    }
}
