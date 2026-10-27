# 02. Why Laravel?

## Overview

Laravel is a PHP framework that provides ready-made tools and
a structured architecture for building web applications and APIs.

The main benefit is that we don't have to build common backend
functionality from scratch.


## 1. What Laravel Provides

Laravel provides built-in support for:

- Routing
- MVC architecture
- Database / Eloquent ORM
- Validation
- Middleware
- Authentication & Authorization
- Sessions
- REST APIs
- Dependency Injection
- Service Container
- Service Providers
- Queues & Jobs
- Events & Listeners
- Cache
- File Storage
- Mail & Notifications
- Task Scheduling
- Testing
- Artisan CLI


## 2. MVC Architecture

Laravel follows MVC:

Model
------
Handles data and database interaction.

View
----
Handles presentation/UI.

Controller
----------
Handles request logic and coordinates Model + View/Response.


Example:

Request
   ↓
Controller
   ↓
Model
   ↓
Database
   ↓
Controller
   ↓
Response / View


## 3. Routing

Laravel provides a clean way to map URLs to application logic.

```php
Route::get('/products', function () {
    return 'Products';
});

Route::post('/products', function () {
    // Create product
});
```

Instead of manually checking:

```php
$_SERVER['REQUEST_URI']
$_SERVER['REQUEST_METHOD']
```
Laravel handles routing for us.


## 4. Eloquent ORM

Eloquent allows us to work with database records using PHP models.

```php
$products = Product::where('status', 'active')->get();
$product = Product::find(10);

$product->name = 'Laptop';
$product->save();
```

Without an ORM, we would need to write and manage SQL queries manually for many operations.


## 5. Validation

Laravel provides built-in request validation.

```php
$request->validate([
    'name'  => 'required|string|max:255',
    'email' => 'required|email',
    'price' => 'required|numeric|min:0',
]);
```
Invalid data automatically produces a validation response.


## 6. Middleware

Middleware runs before or after a request reaches the controller.

Common uses:
- Authentication
- Authorization
- Logging
- Request filtering
- Rate limiting

```php
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return 'Dashboard';
    });

});
```

## 7. Authentication & Authorization

Laravel provides tools for:
- User authentication
- Password hashing
- Sessions
- Permissions / authorization
- API authentication

Example:
```php
if (auth()->check()) {
    $user = auth()->user();
}
```

## 8. REST API Development

Laravel can easily return JSON responses.
```php
return response()->json([
    'success' => true,
    'message' => 'Product created',
    'data' => $product,
]);
```

Typical API flow:

Request
   ↓
Route
   ↓
Controller
   ↓
Validation
   ↓
Service / Model
   ↓
Database
   ↓
JSON Response


## 9. Dependency Injection

Laravel's Service Container automatically resolves dependencies.

Example:
```php
class OrderController
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function store()
    {
        return $this->orderService->create();
    }
}
```
Laravel creates/resolves OrderService and injects it into the controller.*/


## 10. Queues & Jobs

Slow operations should not always run during the HTTP request.

Examples:
- Sending emails
- Processing large files
- Syncing marketplace orders
- Calling external APIs
- Generating reports

```php
SyncShopifyOrders::dispatch($shop);
```
The job can run in the background using a queue worker.


## 11. Events & Listeners
 
Events allow one part of the application to notify other parts that something happened.

Example:

OrderPlaced
    ↓
Listeners
    ├── SendOrderEmail
    ├── UpdateInventory
    └── NotifyShippingSystem

This keeps application logic separated.


## 12. Cache

Cache stores frequently used data so the application doesn't need to perform the same expensive operation repeatedly.

```php
Cache::put('products', $products, 3600);
$products = Cache::get('products');
```

Useful for:
- Database results
- API responses
- Configuration data
- Frequently accessed information


## 13. Artisan

Artisan is Laravel's command-line tool.

Examples:

php artisan serve
php artisan route:list
php artisan make:controller ProductController
php artisan make:model Product
php artisan make:migration create_products_table
php artisan migrate
php artisan queue:work


## 14. Security Features

Laravel provides protection/features for common security needs:

- CSRF protection
- Password hashing
- Request validation
- Authentication
- Authorization
- SQL injection protection through parameterized queries
- Signed URLs
- Rate limiting


## 15. Why Laravel Is Useful in Real Projects

For an e-commerce application:

Products
   ↓
Eloquent

Customer Login
   ↓
Authentication

API Requests
   ↓
Routes + Controllers

Payment Validation
   ↓
Validation

Shipping API
   ↓
HTTP Client / Service

Order Processing
   ↓
Jobs + Queues

Order Created
   ↓
Events + Listeners

Frequently Used Data
   ↓
Cache

Scheduled Sync
   ↓
Scheduler

Laravel provides the structure and tools to connect all these parts together.


## Remember

Laravel is not just "MVC".

The important idea is:

Laravel gives us a complete ecosystem for building backend applications without repeatedly creating common functionality from scratch.

Must-know areas:
   Routing
   MVC
   Eloquent
   Validation
   Middleware
   Authentication
   APIs
   Dependency Injection
   Service Container
   Jobs / Queues
   Events / Listeners
   Cache
   Artisan
   Security

For a Laravel developer, understanding how these features work together is more important than memorizing syntax.