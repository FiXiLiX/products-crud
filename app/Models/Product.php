<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    public function getNextId() 
    {

        $statement = DB::select("show table status like 'products'");
        return $statement[0]->Auto_increment;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setManufacturedAt(string $manufacturedAt): void
    {
        $this->manufactured_at = $manufacturedAt;
    }

    public function setImageUrl(string $ImageUrl): void
    {
        $this->image_url = $ImageUrl;
    }

    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    public function setUser(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getUser(): int
    {
        return $this->user_id;
    }
}
