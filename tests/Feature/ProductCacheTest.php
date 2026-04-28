<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_list_cache_is_invalidated_after_product_created(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Product::factory()
            ->count(3)
            ->create([
                'category_id' => $category->id,
            ]);

        $response1 = $this->getJson('/api/products')
            ->assertOk();

        $this->assertCount(3, $response1->json('data'));

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/products', [
                'name' => 'New Product',
                'price' => 1000,
                'category_id' => $category->id,
            ])
            ->assertCreated();

        $response2 = $this->getJson('/api/products')
            ->assertOk();

        $this->assertCount(4, $response2->json('data'));
    }
}