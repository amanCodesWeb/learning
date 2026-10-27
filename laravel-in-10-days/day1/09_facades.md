# 09. Facades

## What is a Facade?

A **Facade** provides a simple, static-looking way to access services registered in Laravel's **Service Container**.

Example:

```php
Cache::get('products');
Log::info('Order created');
DB::table('orders')->get();
```

Although these look like static method calls, Laravel Facades usually resolve the underlying service from the container.

---

## Common Facades

| Facade    | Used For            |
| --------- | ------------------- |
| `DB`      | Database queries    |
| `Cache`   | Cache operations    |
| `Log`     | Application logging |
| `Storage` | File storage        |
| `Http`    | HTTP/API requests   |
| `Mail`    | Sending emails      |
| `Auth`    | Authentication      |
| `Route`   | Route configuration |
| `Session` | Session data        |

Example:

```php
use Illuminate\Support\Facades\Log;

Log::info('Order created');
```

---

## How a Facade Works

Simplified flow:

Code
  ↓
Facade
  ↓
Service Container
  ↓
Actual Service
  ↓
Method execution

For example:
```
Cache::get('products');
```

Conceptually:

Cache Facade
    ↓
Cache Service
    ↓
get('products')

The Facade hides the container-resolution code from you.

---

## Facade vs Normal Class

Without a Facade, you may inject a service:

```php
class OrderService
{
    public function __construct(
        private CacheManager $cache
    ) {}

    public function getProducts()
    {
        return $this->cache->get('products');
    }
}
```

With the Facade:

```php
use Illuminate\Support\Facades\Cache;

class OrderService
{
    public function getProducts()
    {
        return Cache::get('products');
    }
}
```

The Facade gives a shorter syntax.

---

## Facades Are Not Really Static Services

This:
Cache::get('products');

looks like a normal static method call.
But Laravel's Facade system resolves the underlying object from the Service Container.
This is why Laravel can still provide dependency management behind the scenes.

---

## Facade Example: HTTP API

For a third-party API:

```php
use Illuminate\Support\Facades\Http;

$response = Http::get('https://api.example.com/products');

$products = $response->json();
```

For POST requests:

```php
$response = Http::post(
    'https://api.example.com/orders',
    [
        'order_id' => 1001,
        'amount' => 2500,
    ]
);
```

This is useful for integrations such as shipping, payment, marketplace, and other REST APIs.

---

## Facades vs Dependency Injection

Both approaches are valid.

### Facade
```php
Cache::put('order_1001', $order);
```

### Dependency Injection
```php
class OrderService
{
    public function __construct(
        private CacheManager $cache
    ) {}

    public function store($order)
    {
        $this->cache->put('order_1001', $order);
    }
}
```

### Practical guideline

Use Facades when:
* The operation is simple
* Laravel's facade provides clear syntax
* You don't need to explicitly manage the dependency

Prefer Dependency Injection when:
* Building reusable services
* Writing business/application logic
* You want dependencies to be explicit
* The class needs easy replacement or isolated testing

---

## Creating a Custom Facade

Laravel also allows custom Facades.

Typical structure:

Service Class
      ↓
Service Provider
      ↓
Container Binding
      ↓
Facade
      ↓
Your Application

For most Laravel applications, you do **not** need to create custom Facades unless there is a clear reason.

---

## Common Mistake

Do not assume every class with `::` is a Laravel Facade.

For example:
User::find(1);

may be an **Eloquent model static call**, not a Facade.

Whereas:
Cache::get('key');

uses Laravel's Facade system.

---

## Remember

* Facades provide a **simple interface to Laravel services**.
* They look static but usually resolve services through the **Service Container**.
* Common examples: `DB`, `Cache`, `Log`, `Http`, `Storage`, `Mail`, `Auth`.
* Facades are convenient; Dependency Injection makes dependencies explicit.
* Facades and the Service Container are related, but they are **not the same thing**.