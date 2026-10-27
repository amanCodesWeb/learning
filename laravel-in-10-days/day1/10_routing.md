# 10. Routing

## What is Routing?

A **Route** defines how Laravel responds to an HTTP request.

A route connects:

HTTP Request
    ↓
URL + HTTP Method
    ↓
Route
    ↓
Controller / Closure
    ↓
Response

Routes are mainly defined in:

routes/web.php
routes/api.php

---

## Basic Route

```php
use Illuminate\Support\Facades\Route;

Route::get('/products', function () {
    return 'Products';
});
```

When the client sends:

```
GET /products
```

Laravel executes the route callback.

---

## HTTP Methods

Laravel supports common HTTP methods:

```php
Route::get('/products', ...);
Route::post('/products', ...);
Route::put('/products/{id}', ...);
Route::patch('/products/{id}', ...);
Route::delete('/products/{id}', ...);
```

Typical CRUD mapping:

```php
GET       → Read
POST      → Create
PUT/PATCH → Update
DELETE    → Delete
```

---

## Route to Controller

In real applications, routes usually point to controllers instead of containing business logic.

```php
use App\Http\Controllers\ProductController;

Route::get(
    '/products',
    [ProductController::class, 'index']
);
```

Flow:

GET /products
    ↓
ProductController@index
    ↓
Response

---

## Route Parameters

Parameters allow dynamic values in URLs.

```php
Route::get('/products/{id}', function ($id) {
    return $id;
});
```

Request:
GET /products/25

Result:
25


Multiple parameters:

```php
Route::get('/shops/{shop}/products/{product}', function ($shop, $product) {
    // ...
});
```

---

## Optional Parameters

Use `?` for an optional parameter and provide a default value.

```php
Route::get('/products/{category?}', function ($category = null) {
    return $category;
});
```

Both can work:

/products
/products/electronics

---

## Named Routes

A route can have a name:

```php
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');
```

Generate the URL using:
route('products.index');

Named routes are useful because URLs can change without changing every place where the URL is used.

---

## Route Model Binding

Laravel can automatically resolve a model from a route parameter.

```php
Route::get('/products/{product}', function (Product $product) {
    return $product;
});
```

Request:
/products/25

Laravel attempts to find the corresponding `Product`.
If the model does not exist, Laravel can automatically return a `404` response.

This avoids manually writing:
$product = Product::findOrFail($id);

for the common case.

---

## Custom Route Key

By default, Laravel commonly uses the model's `id`.
You can bind using another field, such as `slug`:

```php
Route::get('/products/{product:slug}', function (Product $product) {
    return $product;
});
```

Request:
/products/iphone-17

Laravel looks for the product using its `slug`.

---

## Route Groups

Groups allow common configuration to be applied to multiple routes.

### Prefix

```php
Route::prefix('admin')->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);

});
```

URLs become:
/admin/products
/admin/orders

---

## Route Name Prefix

```php
Route::name('admin.')->group(function () {

    Route::get('/products', ...)
        ->name('products');

    Route::get('/orders', ...)
        ->name('orders');

});
```

Route names become:
admin.products
admin.orders

---

## Middleware on Routes

Middleware can be applied to a route:

```php
Route::get('/orders', [OrderController::class, 'index'])
    ->middleware('auth');
```

Only authenticated users can access the route.

For multiple routes:

```php
Route::middleware('auth')->group(function () {

    Route::get('/profile', ...);
    Route::get('/orders', ...);

});
```

Middleware details are covered separately in the Middleware topic.

---

## Resource Routes

For standard CRUD operations, Laravel provides resource routing.

```php
Route::resource('products', ProductController::class);
```

This creates conventional routes for:

index   → GET       /products
create  → GET       /products/create
store   → POST      /products
show    → GET       /products/{product}
edit    → GET       /products/{product}/edit
update  → PUT/PATCH /products/{product}
destroy → DELETE    /products/{product}

Useful for standard CRUD controllers.

---

## API Routes

API routes are normally placed in:
routes/api.php

Example:

```php
Route::get('/products', [ProductController::class, 'index']);
```

Typical API request:
GET /api/products

API routes are commonly used for:
* Mobile applications
* React/Vue frontends
* Third-party integrations
* Marketplace integrations
* E-commerce APIs

---

## Route Constraints

You can restrict parameter formats.

Example:

```php
Route::get('/products/{id}', ...)
    ->whereNumber('id');
```

Now the `id` parameter must be numeric.

Other useful constraints include:

```php
->whereAlpha('name')
->whereUuid('id')
->whereIn('status', ['active', 'inactive'])
```

---

## Route Order

Laravel evaluates routes according to their definitions.
Be careful with conflicting dynamic routes.

For example:

```php
Route::get('/products/{product}', ...);
Route::get('/products/create', ...);
```

A specific route such as `/products/create` should be defined before a broad dynamic route when necessary.

---

## Practical E-Commerce Example

```php
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('auth');

Route::get('/orders/{order}', [OrderController::class, 'show'])
    ->middleware('auth');
```

Flow:

GET /products
    ↓
ProductController@index

GET /products/25
    ↓
ProductController@show

POST /orders
    ↓
Authentication Middleware
    ↓
OrderController@store

---

## Remember

* A route maps an **HTTP method + URL** to application logic.
* Use controllers for real application logic.
* Route parameters handle dynamic URLs.
* Named routes generate URLs without hardcoding them.
* Route Model Binding automatically resolves models.
* Route groups organize shared prefixes, names, and middleware.
* Resource routes are useful for standard CRUD.
* `web.php` is commonly used for web routes; `api.php` for API routes.
* Keep routes focused on **mapping requests**, not business logic.