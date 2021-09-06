<?php

namespace App\Commands;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AddNewProduct 
{
    public static function command($request)
    {
        $product = new Product;
        $product->setName($request->name);
        $product->setManufacturedAt($request->manufactured_at);
        $product->setUser($request->user()->id);
        $extension = $request->image->extension();
        $image_name = $product->getNextId() . "." . $extension;
        $request->image->storeAs('/public', $image_name);
        $image_url = Storage::url($image_name);
        $product->setImageUrl($image_name);
        $product->save();

        return $product;
    }
}