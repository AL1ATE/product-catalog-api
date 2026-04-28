## Запуск проекта

### Клонировать репозиторий

```bash
git clone https://github.com/AL1ATE/product-catalog-api.git
cd product-catalog-api
```

### Запустить Docker

```bash
docker compose up -d --build
```

### Установить зависимости

```bash
docker exec -it product_catalog_app bash

composer install
cp .env.example .env
php artisan key:generate
```

### Миграции и сиды

```bash
php artisan migrate:fresh --seed
```

# API

### Получить список товаров

```bash
GET /api/products
```
Параметры:
- category_id
- price_min
- price_max
- search
- sort_by = price | created_at
- sort_direction = asc | desc
- page

### Создать товар (требует авторизацию)

```bash
POST /api/products
```

### Обновить товар (требует авторизацию)

```bash
PUT /api/products/{id}
```

### Удалить товар (требует авторизацию)

```bash
DELETE /api/products/{id}
```

## Примеры curl-запросов

### Получить список товаров

```bash
curl -X GET "http://localhost:8000/api/products" \
  -H "Accept: application/json"
```

---

### Фильтрация по категории

```bash
curl -X GET "http://localhost:8000/api/products?category_id=1" \
  -H "Accept: application/json"
```

### Фильтрация по диапазону цен

```bash
curl -X GET "http://localhost:8000/api/products?price_min=1000&price_max=3000" \
  -H "Accept: application/json"
```

### Поиск по названию

```bash
curl -X GET "http://localhost:8000/api/products?search=test" \
  -H "Accept: application/json"
```

### Сортировка

```bash
curl -X GET "http://localhost:8000/api/products?sort_by=price&sort_direction=asc" \
  -H "Accept: application/json"
```

### Пагинация

```bash
curl -X GET "http://localhost:8000/api/products?page=2" \
  -H "Accept: application/json"
```

---

### Создать товар (требует авторизацию)

```bash
curl -X POST "http://localhost:8000/api/products" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Product",
    "price": 1999.99,
    "category_id": 1
  }'
```


### Обновить товар (требует авторизацию)

```bash
curl -X PUT "http://localhost:8000/api/products/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Product",
    "price": 2499.99
  }'
```


### Удалить товар (требует авторизацию)

```bash
curl -X DELETE "http://localhost:8000/api/products/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TOKEN"
```


# Авторизация
Используется Laravel Sanctum.

Пример получения токена:
```bash
php artisan tinker

$user = \App\Models\User::first();
$user->createToken('test')->plainTextToken;
```

# Кэширование
- Используется Redis
- TTL: 5 минут
- Ключ формируется на основе фильтров
- Инвалидация происходит при:
    - создании товара
    - обновлении
    - удалении

# Тесты
```bash
php artisan test
```
