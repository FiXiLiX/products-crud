<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    public function testUserCanAddProducts()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->json('POST', '/api/products', [
                'image' => UploadedFile::fake()->create('image.jpg', 1010),
                'name' => 'TestName',
                'manufactured_at' => 2000
            ]);
            
        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'TestName',
            'manufactured_at' => 2000,
            'id' => 1,
            'image_url' => '1.jpg'
        ]);
    }

    public function testUserCanEditProducts()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            "user_id" => $user->id
        ]);
            
        $response = $this->actingAs($user)
            ->json('PUT', '/api/products/' . $product->id, [
                'name' => 'TestName23',
                'manufactured_at' => 2010,
            ]);
        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'TestName23',
            'manufactured_at' => 2010,
            'id' => $product->id,
            'image_url' => '1.png'
        ]);
    }

    public function testUserCanDeleteProducts()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            "user_id" => $user->id
        ]);
            
        $response = $this->actingAs($user)
            ->json('DELETE', '/api/products/' . $product->id);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => true,
        ]);
    }

    public function testUserCanListAllOfHisProducts()
    {
        $user = User::factory()->create();

        $products = Product::factory(10)->create([
            "user_id" => $user->id
        ]);

        $response = $this
            ->actingAs($user)
            ->json('GET', '/api/products');
            
        $response->assertStatus(200);
        $response->assertJson($products->toArray());
        $response->assertJsonCount(10);
        $response->assertJsonStructure([
            '*' => [ 'id', 'name', 'created_at', 'updated_at', 'image_url' ],
        ]);
    }
}
