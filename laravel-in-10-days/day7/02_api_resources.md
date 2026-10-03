# API Resources

## Overview

API Resources are used to control how Laravel models are converted into JSON responses.

Instead of returning the entire model:
    return $product;

we define exactly what the API should expose:
    return new ProductResource($product);

A Resource acts as a transformation layer between the Eloquent model and the API response.

    Database
        ↓
    Eloquent Model
        ↓
    API Resource
        ↓
    JSON Response

## Why Use API Resources?

Suppose a Product model contains:

    id
    name
    price
    cost_price
    internal_notes
    created_at
    updated_at

We may only want the API to return:

    {
        "id": 1,
        "name": "Laptop",
        "price": 50000
    }

This prevents unnecessary or sensitive model fields from being exposed.

## Creating a Resource

Create a Resource using Artisan:
    php artisan make:resource ProductResource

File:
    app/Http/Resources/ProductResource.php

Example:

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

## Using a Resource

For a single model:

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

The response will look like:

    {
        "data": {
            "id": 1,
            "name": "Laptop",
            "price": 50000
        }
    }

## Resource Collection

For multiple models:

    public function index()
    {
        return ProductResource::collection(Product::all());
    }

This returns:

    {
        "data": [
            {
                "id": 1,
                "name": "Laptop",
                "price": 50000
            },
            {
                "id": 2,
                "name": "Phone",
                "price": 20000
            }
        ]
    }

Use:

    new ProductResource($product)

for one model.

Use:

    ProductResource::collection($products)

for multiple models.

## Resources with Relationships

Suppose Product belongs to Category.

The Resource can include the category:

    return [
        'id' => $this->id,
        'name' => $this->name,
        'price' => $this->price,
        'category' => new CategoryResource(
            $this->whenLoaded('category')
        ),
    ];

Load the relationship in the controller:

    $product = Product::with('category')->findOrFail($id);

    return new ProductResource($product);

`whenLoaded()` means the relationship is included only when it has already been loaded.

## Model vs Resource

Model:

    Model
      ↓
    Represents database data

Resource:

    Resource
      ↓
    Represents API output

Think of it as:

    Model
    → What data do we have?

    Resource
    → What data should the API return?

## Key Concepts

- API Resources transform Eloquent models into API responses.
- They control which fields are exposed.
- They help keep API responses consistent.
- `new ProductResource($product)` is used for one model.
- `ProductResource::collection($products)` is used for multiple models.
- `whenLoaded()` is useful when returning relationships.
- A Resource is not a replacement for an Eloquent Model.

## Interview Question

### What is an API Resource in Laravel?

An API Resource is a class used to transform and control the data returned by an API from Eloquent models.