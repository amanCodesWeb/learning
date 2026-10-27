# HTTP Client

## Overview

Laravel's HTTP Client provides a simple way to send HTTP requests to external APIs.

It is built on top of **Guzzle**, but Laravel provides a cleaner interface for making API requests.

Common use cases:
- Calling a third-party API
- Sending data to another application
- Getting data from an external service
- Integrating payment, shipping, or marketplace APIs

## Basic GET Request

Use the `Http` facade:

    use Illuminate\Support\Facades\Http;

    $response = Http::get('https://api.example.com/products');

The response is stored in `$response`.

To get the response as an array:

    $data = $response->json();

## POST Request

Send data to an API using `post()`:

    $response = Http::post('https://api.example.com/products', [
        'name' => 'Laptop',
        'price' => 50000,
    ]);

Laravel automatically sends the data as a request body.

## PUT and DELETE

Update data:

    $response = Http::put('https://api.example.com/products/1', [
        'name' => 'Updated Laptop',
    ]);

Delete data:

    $response = Http::delete(
        'https://api.example.com/products/1'
    );

## Headers

Some APIs require specific headers.

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'X-API-Key' => $apiKey,
    ])->get('https://api.example.com/products');

Headers contain additional information about the request.

## Bearer Token

For APIs that use Bearer Token authentication:

    $response = Http::withToken($token)
        ->get('https://api.example.com/products');

The request contains:

    Authorization: Bearer TOKEN

## Checking the Response

Laravel provides useful methods for checking the response status.

    if ($response->successful()) {
        $data = $response->json();
    }

Other useful methods:

    $response->failed();
    $response->clientError();
    $response->serverError();

Common meanings:

    successful()
    → 2xx response

    clientError()
    → 4xx response

    serverError()
    → 5xx response

## Status Code

You can also get the exact HTTP status code:

    $status = $response->status();

Example:

    if ($response->status() === 200) {
        // Request successful
    }

## Timeout

External APIs may become slow or unavailable.

Set a timeout:

    $response = Http::timeout(10)
        ->get('https://api.example.com/products');

This prevents your application from waiting indefinitely.

## Retry

Temporary network problems can be handled using `retry()`:

    $response = Http::retry(3, 100)
        ->get('https://api.example.com/products');

This means Laravel can retry the request up to 3 times.

The second value specifies the delay between attempts in milliseconds.

## Request Flow

A typical HTTP Client request looks like:

    Laravel Application
          ↓
    Http Client
          ↓
    External API
          ↓
    HTTP Response
          ↓
    $response->json()

## Important Concept

The Laravel HTTP Client is mainly used when **your Laravel application needs to communicate with another API**.

Example:

    Laravel
       ↓
    HTTP Client
       ↓
    Shopify API

or:

    Laravel
       ↓
    HTTP Client
       ↓
    ShipStation API

## Key Points

- Laravel HTTP Client is accessed through the `Http` facade.
- `get()` retrieves data.
- `post()` sends/creates data.
- `put()` updates data.
- `delete()` deletes data.
- `withHeaders()` adds request headers.
- `withToken()` sends a Bearer token.
- `json()` reads JSON response data.
- `successful()` checks for a successful response.
- `timeout()` prevents requests from waiting indefinitely.
- `retry()` retries failed requests.

## Interview Question

### What is Laravel HTTP Client?

Laravel HTTP Client is a convenient Laravel interface for making HTTP requests to external APIs. It is built on top of Guzzle and provides methods for requests, authentication, headers, timeouts, retries, and response handling.