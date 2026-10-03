# Third-Party APIs

## Overview

A **third-party API** is an API provided by another application or company that our Laravel application communicates with.

Examples:

    Laravel
       ↓
    Shopify API

    Laravel
       ↓
    ShipStation API

    Laravel
       ↓
    Payment Gateway API

Third-party APIs allow us to use external services without building those services ourselves.

## Why Use Third-Party APIs?

Instead of building everything inside our application, we can integrate existing services.

For example:

- Shopify → products and orders
- ShipStation → shipping
- Stripe → payments
- Google Maps → maps and locations

The Laravel application communicates with these services using HTTP requests.

## Basic Integration Flow

    Laravel Application
          ↓
    HTTP Client
          ↓
    Third-Party API
          ↓
    API Response
          ↓
    Process Response
          ↓
    Store/Return Data

Laravel's HTTP Client is commonly used for these integrations.

## API Credentials

Third-party APIs usually require credentials such as:

    API Key
    API Secret
    Access Token
    Client ID
    Client Secret

These credentials should not be hard-coded in the application.

Store them in `.env`:

    SHOPIFY_API_KEY=your-key
    SHOPIFY_API_SECRET=your-secret

Then define them in `config/services.php`:

    'shopify' => [
        'key' => env('SHOPIFY_API_KEY'),
        'secret' => env('SHOPIFY_API_SECRET'),
    ],

Access them through configuration:

    $key = config('services.shopify.key');

## Creating an API Service

Keep third-party API logic separate from controllers.

Create a service:

    php artisan make:class Services/ShopifyService

Example:

    namespace App\Services;

    use Illuminate\Support\Facades\Http;

    class ShopifyService
    {
        public function getProducts()
        {
            return Http::withToken(
                config('services.shopify.key')
            )->get('https://example.com/api/products');
        }
    }

The controller can then use the service:

    public function index(ShopifyService $shopify)
    {
        $response = $shopify->getProducts();

        return response()->json(
            $response->json()
        );
    }

## Why Use a Service?

Without a service:

    Controller
        ↓
    API Request
        ↓
    Authentication
        ↓
    Response Processing
        ↓
    Business Logic

The controller can become difficult to maintain.

With a service:

    Controller
        ↓
    Service
        ↓
    Third-Party API

The service contains the integration logic.

## Handling API Errors

A third-party API can fail because of:

- Invalid credentials
- Invalid request data
- Rate limits
- Network problems
- Server errors
- API downtime

Always check the response:

    $response = Http::get($url);

    if ($response->failed()) {
        // Handle API failure
    }

For example:

    if ($response->status() === 401) {
        // Invalid or expired credentials
    }

    if ($response->status() === 429) {
        // Rate limit exceeded
    }

## Important Concept

A third-party API is an external dependency.

Our application should not assume that the external service will always be available.

    Laravel
       ↓
    External API
       ↓
    May succeed or fail

Therefore we should handle:

    Authentication
    Validation
    Errors
    Timeouts
    Retries
    Rate limits

## Key Points

- A third-party API belongs to an external service.
- Laravel communicates with it using HTTP requests.
- API credentials should be stored securely in `.env`.
- Access configuration through `config()`.
- Keep integration logic inside a service instead of controllers.
- Always handle API failures and external service downtime.

## Interview Question

### What is a third-party API?

A third-party API is an API provided by an external service that allows our Laravel application to communicate with and use that service's functionality or data.