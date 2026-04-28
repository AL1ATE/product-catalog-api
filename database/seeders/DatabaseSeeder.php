<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        Category::factory()
            ->count(5)
            ->create()
            ->each(function (Category $category): void {
                Product::factory()
                    ->count(20)
                    ->create([
                        'category_id' => $category->id,
                    ]);
            });
    }
}