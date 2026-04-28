<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\IndexProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    public function index(IndexProductRequest $request): AnonymousResourceCollection
    {
        return ProductResource::collection(
            $this->productService->getPaginated($request->validated() + [
                'page' => $request->integer('page', 1),
            ])
        );
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        return ProductResource::make(
            $this->productService->create($request->validated())
        );
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        return ProductResource::make(
            $this->productService->update($product, $request->validated())
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return response()->json(status: 204);
    }
}