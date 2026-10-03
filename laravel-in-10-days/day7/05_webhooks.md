# 7.5 Webhooks

## Overview

A **webhook** is a way for one application to automatically send data to another application when an event occurs.

With a normal API request:

    Laravel
       ↓
    External API

Laravel asks the external API for something.

With a webhook:

    External Service
       ↓
    Laravel

The external service notifies Laravel when something happens.

## Example

Suppose a Shopify order is created.

Without a webhook:

    Laravel
       ↓
    Shopify API
       ↓
    "Are there any new orders?"

With a webhook:

    Customer places order
            ↓
        Shopify
            ↓
    Webhook sent to Laravel
            ↓
    Laravel processes the order

Laravel does not need to continuously ask Shopify for new orders.

## Creating a Webhook Route

Define a POST route:

    Route::post(
        '/webhooks/shopify',
        [ShopifyWebhookController::class, 'handle']
    );

Create the controller:

    php artisan make:controller ShopifyWebhookController

Example:

    public function handle(Request $request)
    {
        $data = $request->all();

        // Process webhook

        return response()->json([
            'message' => 'Webhook received'
        ]);
    }

## Webhook Data

The external service sends data in the request body.

Example:

    {
        "event": "order.created",
        "order_id": 12345,
        "customer": "John"
    }

Laravel can access it using:

    $data = $request->all();

Or:

    $orderId = $request->input('order_id');

## Webhook vs API

The main difference is who starts the communication.

    API:

    Laravel
       ↓
    External Service

    Laravel starts the request.

    Webhook:

    External Service
       ↓
    Laravel

    External Service starts the request.

Think:

    API
    → "Give me the data."

    Webhook
    → "Something happened."

## Webhook Security

A webhook endpoint is publicly accessible because the external service needs to call it.

Therefore, you should verify that the request actually came from the expected service.

Many services send a signature with the webhook.

Basic flow:

    Webhook Request
          ↓
    Verify Signature
          ↓
       Valid?
       ↙   ↘
     Yes    No
      ↓      ↓
    Process Reject

Never blindly trust webhook data.

## Duplicate Webhooks

A webhook provider may send the same event more than once.

Example:

    Order #1001 webhook
          ↓
       Processed

    Order #1001 webhook again
          ↓
       Already processed
          ↓
          Skip

Your application should make webhook processing **idempotent**.

A common approach is to store a unique event ID or external resource ID and check whether it has already been processed.

## Webhooks and Queues

Webhook processing can sometimes take time.

Instead of doing heavy processing directly:

    Webhook
       ↓
    Process everything
       ↓
    Response

Use a queue:

    Webhook
       ↓
    Dispatch Job
       ↓
    Return response
       ↓
    Queue Worker
       ↓
    Process webhook

Example:

    ProcessShopifyOrder::dispatch($request->all());

    return response()->json([
        'message' => 'Webhook received'
    ]);

This allows the webhook endpoint to respond quickly while the actual processing happens in the background.

## Common Webhook Events

Examples:

    order.created
    order.updated
    payment.completed
    payment.failed
    shipment.created
    customer.created

The exact events depend on the third-party service.

## Key Points

- A webhook is an HTTP callback from an external service to your application.
- Webhooks are commonly sent when an event occurs.
- Webhooks usually use POST requests.
- Always verify webhook authenticity when the provider supports signatures.
- Handle duplicate events using idempotency.
- Use queues when webhook processing is slow or involves multiple operations.

## Interview Question

### What is a webhook?

A webhook is a mechanism where an external service sends an HTTP request to our application when a specific event occurs.

### API vs Webhook?

    API:
    Our application requests data from another service.

    Webhook:
    Another service sends data to our application when something happens.