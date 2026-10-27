# 04. MVC Architecture

## Overview

MVC stands for:

- Model
- View
- Controller

Laravel follows the MVC architecture to separate different responsibilities of an application.

The main goal is to keep application code organized and easier to maintain.


## Model

The **Model** represents application data and handles interaction with the database.

Example:
    Product
    Order
    User
    Category

A model usually represents a database table.

Example:

    Product Model
         ↓
    products table

The model can be used to:
- Retrieve data
- Create records
- Update records
- Delete records
- Define relationships
- Add model-specific logic


## View

The **View** is responsible for presenting data to the user.

Laravel commonly uses **Blade** templates for views.

Example:
    resources/views/products/show.blade.php

Example Blade:
    <h1>{{ $product->name }}</h1>
    <p>Price: {{ $product->price }}</p>

The View should mainly handle presentation.
Business logic should not normally be placed inside the View.


## Controller

The **Controller** handles the request and coordinates the application logic.
It decides what should happen when a request reaches a particular application action.

Example:
    ProductController

A controller can:
- Receive the request
- Validate input
- Call application/business logic
- Retrieve data
- Return a View
- Return a JSON response


## How MVC Works Together

Example: Displaying a product.

    User
      ↓
    Controller
      ↓
    Model
      ↓
    Database
      ↓
    Model
      ↓
    Controller
      ↓
    View
      ↓
    User


## Example

Controller:

    class ProductController
    {
        public function show(Product $product)
        {
            return view('products.show', [
                'product' => $product
            ]);
        }
    }

Model:

    class Product extends Model
    {
        // Product model
    }

View:

    <h1>{{ $product->name }}</h1>
    <p>{{ $product->price }}</p>


## MVC Responsibilities

    Model
    ├── Data
    ├── Database interaction
    └── Relationships

    Controller
    ├── Request handling
    ├── Coordinates application flow
    └── Returns response

    View
    ├── UI
    └── Data presentation


## Why Separate Responsibilities?

Without separation, one file could contain:

    Request handling
    Database queries
    Business logic
    HTML
    Response handling

This becomes difficult to understand and maintain.

MVC separates these responsibilities so each part has a clearer purpose.


## MVC in an API

MVC is also useful when building APIs.
There may be no Blade View.

Instead:
    Client
      ↓
    Controller
      ↓
    Model / Business Logic
      ↓
    Database
      ↓
    Controller
      ↓
    JSON Response

Example:

    public function show(Product $product)
    {
        return response()->json([
            'data' => $product
        ]);
    }

Here the API response replaces the traditional HTML View.


## Important Distinction

MVC does **not** mean that all business logic must be placed inside controllers.

For larger applications, controllers should remain relatively thin.

Complex business logic can be moved into:
    Services
    Actions
    Jobs
    Domain classes

For example:

    Controller
        ↓
    OrderService
        ↓
    Order Model / Database

This keeps the controller focused on handling the request.


## Remember

    Model      → Data and database interaction
    View       → Presentation / UI
    Controller → Handles request and coordinates the flow


Basic MVC flow:

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
    View / JSON Response

The key idea is **separation of responsibilities**.