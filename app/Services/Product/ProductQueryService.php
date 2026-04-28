<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductQueryService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        return Product::query()
            ->with('category')
            ->when(isset($filters['category_id']), function ($query) use ($filters): void {
                $query->where('category_id', $filters['category_id']);
            })
            ->when(isset($filters['price_min']), function ($query) use ($filters): void {
                $query->where('price', '>=', $filters['price_min']);
            })
            ->when(isset($filters['price_max']), function ($query) use ($filters): void {
                $query->where('price', '<=', $filters['price_max']);
            })
            ->when(!empty($filters['search']), function ($query) use ($filters): void {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(15);
    }
}