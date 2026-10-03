# REST APIs & External API Integration

## 7.1 REST API Fundamentals

### What is an API?

**API (Application Programming Interface)** allows two applications to communicate with each other.

Example:

```text
Mobile App
    ↓
Laravel API
    ↓
Database
```

The mobile app sends a request to Laravel, and Laravel returns data, usually as JSON.

### What is REST?

**REST (Representational State Transfer)** is an architectural style commonly used for web APIs.

REST APIs usually use HTTP methods:

| Method | Purpose               | Example             |
| ------ | --------------------- | ------------------- |
| GET    | Retrieve data         | Get products        |
| POST   | Create data           | Create product      |
| PUT    | Replace/update data   | Update product      |
| PATCH  | Partially update data | Update product name |
| DELETE | Delete data           | Delete product      |

### Example REST endpoints

```text
GET     /api/products
GET     /api/products/10
POST    /api/products
PUT     /api/products/10
PATCH   /api/products/10
DELETE  /api/products/10
```

### Request and Response

Client request:

```http
GET /api/products
```

Laravel response:

```json
{
    "data": [
        {
            "id": 1,
            "name": "Laptop",
            "price": 50000
        }
    ]
}
```

---

## 7.2 API Routes & Controllers

API routes are normally defined in:

```text
routes/api.php
```

Example:

```php
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
```

### Resource Routes

Instead of defining every route manually:

```php
Route::apiResource('products', ProductController::class);
```

This creates the standard API CRUD routes.

### API Controller

```bash
php artisan make:controller ProductController --api
```

Example:

```php
class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());

        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted'
        ]);
    }
}
```

### Route Model Binding

Laravel can automatically find a model from the route parameter.

```php
Route::get('/products/{product}', [ProductController::class, 'show']);
```

```php
public function show(Product $product)
{
    return $product;
}
```

Laravel automatically finds the product by ID.

---

## 7.3 Request Validation & JSON Responses

Never trust data coming from an API request.

Validate it before processing.

```php
$request->validate([
    'name' => 'required|string|max:255',
    'price' => 'required|numeric|min:0',
]);
```

Example:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
    ]);

    $product = Product::create($validated);

    return response()->json([
        'message' => 'Product created',
        'data' => $product
    ], 201);
}
```

### Common HTTP Status Codes

| Code | Meaning                            |
| ---- | ---------------------------------- |
| 200  | Successful request                 |
| 201  | Resource created                   |
| 204  | Successful request with no content |
| 400  | Bad request                        |
| 401  | Unauthenticated                    |
| 403  | Forbidden                          |
| 404  | Resource not found                 |
| 422  | Validation error                   |
| 429  | Too many requests                  |
| 500  | Server error                       |

### JSON Response

```php
return response()->json([
    'message' => 'Product created',
    'data' => $product
], 201);
```

Error example:

```php
return response()->json([
    'message' => 'Product not found'
], 404);
```

### Validation Error

Laravel automatically returns a `422` JSON response when an API request fails validation.

Example:

```json
{
    "message": "The name field is required.",
    "errors": {
        "name": [
            "The name field is required."
        ]
    }
}
```

---

## 7.4 API Resources

API Resources control how Eloquent models are converted into JSON.

Create a resource:

```bash
php artisan make:resource ProductResource
```

Example:

```php
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
```

Controller:

```php
public function show(Product $product)
{
    return new ProductResource($product);
}
```

For collections:

```php
public function index()
{
    return ProductResource::collection(Product::all());
}
```

### Why use Resources?

Without a resource:

```php
return $product;
```

The API may expose fields you don't want to expose.

With a resource:

```php
return new ProductResource($product);
```

You control exactly what the API returns.

Example response:

```json
{
    "data": {
        "id": 1,
        "name": "Laptop",
        "price": 50000
    }
}
```

---

## 7.5 API Authentication

Authentication answers:

> "Who is making this API request?"

Common API authentication methods include:

API Key
Bearer Token
OAuth 2.0
Sanctum
Passport
JWT

### Laravel Sanctum

Sanctum is commonly used for simple API token authentication.

Example:

```php
$token = $user->createToken('api-token')->plainTextToken;
```

Return the token:

```php
return response()->json([
    'token' => $token
]);
```

Client sends:

```http
Authorization: Bearer TOKEN
```

Protect routes:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
});
```

## 7.6 Laravel HTTP Client

Laravel provides an HTTP Client for communicating with external APIs.

It is built on top of Guzzle.

Example:

```php
use Illuminate\Support\Facades\Http;

$response = Http::get('https://api.example.com/products');
```

Get JSON:

```php
$data = $response->json();
```

Check success:

```php
if ($response->successful()) {
    $data = $response->json();
}
```

### POST Request

```php
$response = Http::post('https://api.example.com/products', [
    'name' => 'Laptop',
    'price' => 50000,
]);
```

### Headers

```php
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $token,
    'Accept' => 'application/json',
])->get('https://api.example.com/products');
```

### Bearer Token

```php
$response = Http::withToken($token)
    ->get('https://api.example.com/products');
```

### Timeout

```php
$response = Http::timeout(10)
    ->get('https://api.example.com/products');
```

### Retry

```php
$response = Http::retry(3, 100)
    ->get('https://api.example.com/products');
```

This attempts the request up to 3 times if it fails.

### Check Response

```php
if ($response->successful()) {
    // 2xx response
}

if ($response->failed()) {
    // 4xx or 5xx response
}
```

---

## 7.7 Third-Party API Integration

A third-party API allows Laravel to communicate with another service.

Example:

Laravel
   ↓
ShipStation API
   ↓
Shipping Label

or:

Laravel
   ↓
Shopify API
   ↓
Products / Orders

### Basic Integration Flow

1. Store API credentials
        ↓
2. Create API service
        ↓
3. Send request
        ↓
4. Receive response
        ↓
5. Validate response
        ↓
6. Process data
        ↓
7. Store/update local data

### Store Credentials in `.env`

```env
SHIPSTATION_API_KEY=your-key
SHIPSTATION_API_SECRET=your-secret
```

Do not hard-code secrets:

```php
// Bad
$apiKey = 'abc123';
```

Instead:

```php
$apiKey = config('services.shipstation.key');
```

### Add Configuration

`config/services.php`

```php
'shipstation' => [
    'key' => env('SHIPSTATION_API_KEY'),
    'secret' => env('SHIPSTATION_API_SECRET'),
],
```

Then:

```php
$key = config('services.shipstation.key');
```

### Why use `config()` instead of `env()` directly?

Keep environment-specific values in `.env`, but access them through Laravel's configuration layer.

.env
    ↓
config/services.php
    ↓
Application/Service

This gives the application a consistent configuration interface and works correctly with Laravel's configuration caching.

### Create an API Service

Example:

```php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShippingService
{
    public function createShipment(array $data)
    {
        return Http::withToken(
            config('services.shipstation.key')
        )->post(
            'https://api.example.com/shipments',
            $data
        );
    }
}
```

Controller:

```php
public function createShipment(Request $request, ShippingService $shipping)
{
    $response = $shipping->createShipment(
        $request->all()
    );

    return response()->json(
        $response->json()
    );
}
```

### Why use a Service?

Avoid putting external API logic directly inside controllers.

Bad:

```php
public function store()
{
    // 100 lines of API logic
}
```

Better:

Controller
    ↓
Service
    ↓
External API

The controller handles the HTTP request.

The service handles the external API logic.

---

## 7.8 Webhooks

A **webhook** is an HTTP request sent by another system to your application when an event happens.

Normal API:

Laravel → Shopify

Webhook:

Shopify → Laravel

### Example:

Shopify
   ↓
Order Created
   ↓
POST /api/webhooks/shopify/order-created
   ↓
Laravel
   ↓
Process Order

### Webhook Route

```php
Route::post(
    '/webhooks/shopify/order-created',
    [ShopifyWebhookController::class, 'orderCreated']
);
```

### Controller

```php
public function orderCreated(Request $request)
{
    $order = $request->all();

    // Process order

    return response()->json([
        'message' => 'Webhook received'
    ]);
}
```

### Webhook Security

Never blindly trust webhook requests.
Many APIs provide a signature that can be verified.

Example concept:

Webhook Request
      ↓
Signature
      ↓
Verify Secret
      ↓
Valid?
   ↙     ↘
 Yes      No
 ↓        ↓
Process   Reject

### Important Webhook Concepts

#### Idempotency

A webhook may be delivered more than once.
Your application should avoid processing the same event multiple times.

Example:

Webhook #123
    ↓
Process order

Webhook #123 again
    ↓
Already processed
    ↓
Do nothing

Store an event ID or unique external ID to detect duplicates.

#### Fast Response

Do not perform heavy processing directly inside the webhook request if it can be avoided.

Better:

Webhook
   ↓
Validate
   ↓
Store/dispatch job
   ↓
Return 200
   ↓
Queue processes event

Example:

```php
ProcessShopifyOrder::dispatch($request->all());

return response()->json([
    'message' => 'Webhook received'
]);
```

---

# API Integration Architecture

A common Laravel API architecture:

Request
   ↓
Route
   ↓
Middleware
   ↓
Controller
   ↓
Validation
   ↓
Service
   ↓
External API / Model
   ↓
Resource
   ↓
JSON Response

## Example:

POST /api/orders
       ↓
OrderController
       ↓
Validate Request
       ↓
OrderService
       ↓
Shopify API
       ↓
Save Order
       ↓
OrderResource
       ↓
JSON Response

---

# API Best Practices

### 1. Validate input

```php
$request->validate([
    'email' => 'required|email',
]);
```

### 2. Never expose secrets

Keep API keys and passwords in `.env`.

### 3. Use API Resources

Control exactly what data is returned.

### 4. Use proper HTTP status codes

```text
200 → Success
201 → Created
401 → Unauthenticated
403 → Forbidden
404 → Not Found
422 → Validation Error
500 → Server Error
```

### 5. Handle external API failures

```php
$response = Http::timeout(10)->get($url);

if ($response->failed()) {
    // Handle failure
}
```

### 6. Use retries when appropriate

```php
Http::retry(3, 100)->get($url);
```

### 7. Keep API logic out of controllers

Use services:
Controller → Service → API

### 8. Use queues for slow operations

Request
  ↓
Dispatch Job
  ↓
Return response
  ↓
Queue Worker
  ↓
External API

### 9. Protect webhooks

Verify signatures and prevent duplicate processing.

### 10. Log API failures

Useful information:
API name
Request ID
Endpoint
Status code
Error message
Timestamp

Never log sensitive credentials or tokens.

---

# Quick Interview Questions

### What is REST API?

A REST API is an API that follows REST principles and uses HTTP methods such as GET, POST, PUT, PATCH, and DELETE to work with resources.

### GET vs POST?

GET  → Retrieve data
POST → Create/send data

### PUT vs PATCH?

PUT   → Usually replaces the resource
PATCH → Partially updates the resource

### What is API Resource in Laravel?

A Laravel API Resource transforms a model or collection into a controlled JSON response.

### What is middleware?

Middleware filters or processes an HTTP request before it reaches the controller.

Example:

Request
  ↓
Auth Middleware
  ↓
Controller

### What is Laravel Sanctum?

Sanctum provides lightweight authentication for Laravel applications, including API token authentication.

### What is the Laravel HTTP Client?

Laravel's HTTP Client provides a convenient interface for making HTTP requests to external APIs.

```php
Http::get($url);
Http::post($url, $data);
```

### Why use a service for API integration?

To separate external API/business logic from controllers and make the code easier to maintain, test, and reuse.

### What is a webhook?

A webhook is an HTTP callback where an external service sends data to your application when an event occurs.

### API vs Webhook?

API:
Your application asks for data.

Webhook:
External application tells your application that something happened.

### What is idempotency?

Idempotency means processing the same request/event multiple times should not create unintended duplicate effects.

Example:

Payment webhook received
       ↓
Payment processed

Same webhook received again
       ↓
Already processed
       ↓
Skip duplicate processing

---