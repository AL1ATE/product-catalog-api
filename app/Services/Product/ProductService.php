<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        private readonly ProductCacheService $productCacheService,
        private readonly ProductQueryService $productQueryService
    ) {
    }

    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return $this->productCacheService->rememberPaginated(
            $filters,
            fn (): LengthAwarePaginator => $this->productQueryService->getPaginated($filters)
        );
    }

    public function create(array $data): Product
    {
        $product = Product::query()->create($data);

        $this->productCacheService->clearListCache();

        return $product->load('category');
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        $this->productCacheService->clearListCache();

        return $product->load('category');
    }

    public function delete(Product $product): void
    {
        $product->delete();

        $this->productCacheService->clearListCache();
    }
}