<?php

namespace App\Validators;

class ProductValidator {

    static function data() 
    {
        return [
            'name' => ['required', 'max:255'],
            'manufactured_at' => ['required', 'integer', 'min:1901', 'max:2021'],
        ];
    }

    static function image()
    {
        return [
            'image' => 'mimes:jpeg,png|max:1014'
        ];
    }

}