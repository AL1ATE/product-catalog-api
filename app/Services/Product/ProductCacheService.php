<?php

namespace App\Services\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductCacheService
{
    private const TTL_SECONDS = 300;

    public function rememberPaginated(array $filters, callable $callback): LengthAwarePaginator
    {
        return Cache::tags($this->tags())->remember(
            $this->makeKey($filters),
            self::TTL_SECONDS,
            $callback
        );
    }

    public function clearListCache(): void
    {
        Cache::tags($this->tags())->flush();
    }

    private function makeKey(array $filters): string
    {
        ksort($filters);

        return 'products:list:' . md5(json_encode($filters));
    }

    private function tags(): array
    {
        return ['products'];
    }
}