<?php

namespace App\Commands;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateProduct 
{
    public static function command($product, $request, $updateImage)
    {
        $product->setName($request->name);
        $product->setManufacturedAt($request->manufactured_at);
        if ($updateImage) {
            $extension = $request->image->extension();
            $image_name = $product->getId() . "." . $extension;
            $request->image->storeAs('/public', $image_name);
            $image_url = Storage::url($image_name);
            $product->setImageUrl($image_name);
        }
        $product->save();
        return $product;
    }
}