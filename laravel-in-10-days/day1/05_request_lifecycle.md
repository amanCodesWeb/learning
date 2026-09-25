# 05. Laravel Request Lifecycle

## Overview

The Laravel request lifecycle describes what happens from the moment a request enters the application until Laravel sends a response back to the client.

The simplified flow is:

    Client
      ↓
    public/index.php
      ↓
    Application Bootstrap
      ↓
    Service Providers
      ↓
    Middleware
      ↓
    Router
      ↓
    Controller
      ↓
    Application Logic
      ↓
    Response
      ↓
    Middleware
      ↓
    Client


## 1. Request Enters Laravel

A web request normally enters Laravel through:
    public/index.php

This is the main entry point of the application.
The web server should point requests to the `public` directory.


## 2. Application Is Bootstrapped

Laravel creates and bootstraps the application.

The application is configured through:
    bootstrap/app.php

During this stage Laravel prepares the application so that its services and components can be used.


## 3. Service Providers Are Loaded

Laravel loads its configured service providers.
Service providers are responsible for registering and bootstrapping services used by the application.

Examples:
    Database
    Queue
    Cache
    Application services
    Custom application bindings

At this stage Laravel prepares the services required by the application.


## 4. Middleware

The request passes through middleware.

Middleware can inspect or modify the request before it reaches the application.

Common middleware responsibilities:
    Authentication
    Authorization
    CSRF protection
    Rate limiting
    Request filtering
    Session handling

Example flow:

    Request
       ↓
    Auth Middleware
       ↓
    Controller


If middleware rejects the request, the controller may never execute.

Example:
    Unauthenticated User
          ↓
    auth middleware
          ↓
    Redirect / 401 Response


## 5. Router

Laravel's router determines which route should handle the request.

For example:
    GET /products/10

Laravel finds the matching route and determines the action that should handle it.

The action can be:
    Closure
    Controller method


## 6. Controller

If the route points to a controller, Laravel calls the appropriate
controller method.

Example:
    Route
       ↓
    ProductController@show

The controller can then:
    Validate the request
        ↓
    Call business logic
        ↓
    Retrieve/update data
        ↓
    Return response


## 7. Application Logic

The controller may interact with other parts of the application.

Example:
    Controller
       ↓
    Service
       ↓
    Model
       ↓
    Database

For a more complex application:

    Controller
       ↓
    OrderService
       ↓
    Payment Service
       ↓
    Order Model
       ↓
    Database


## 8. Response Is Created

After the application finishes processing the request, Laravel creates a response.

A response can be:
    HTML
    JSON
    Redirect
    File
    Stream
    Plain text

Example JSON response:

    return response()->json([
        'success' => true,
        'message' => 'Product created'
    ]);


## 9. Response Passes Through Middleware

After the application generates the response, middleware can also process the response before it is returned to the client.

Simplified:
    Request
       ↓
    Middleware
       ↓
    Controller
       ↓
    Response
       ↓
    Middleware
       ↓
    Client


This is why middleware can perform actions both before and after the main application logic.


## 10. Response Is Sent to Client

Finally, Laravel sends the response back to the client.

    Laravel
       ↓
    HTTP Response
       ↓
    Browser / Mobile App / API Client


## Complete Simplified Lifecycle

    Client Request
          ↓
    public/index.php
          ↓
    Bootstrap Application
          ↓
    Load Service Providers
          ↓
    Middleware
          ↓
    Router
          ↓
    Controller
          ↓
    Service / Model
          ↓
    Database
          ↓
    Response
          ↓
    Middleware
          ↓
    Client


## Example: API Request

Suppose a mobile application sends:

    GET /api/products/10

The simplified lifecycle is:

    Mobile App
        ↓
    public/index.php
        ↓
    Laravel Application
        ↓
    Middleware
        ↓
    API Route
        ↓
    ProductController
        ↓
    Product Model
        ↓
    Database
        ↓
    JSON Response
        ↓
    Mobile App


## Important Point

The lifecycle is more detailed internally than the simplified flow shown here.

For development and interviews, understand the major stages:

    Request
       ↓
    Bootstrap
       ↓
    Service Providers
       ↓
    Middleware
       ↓
    Routing
       ↓
    Controller
       ↓
    Application Logic
       ↓
    Response


Understand **what happens at each stage** rather than memorizing Laravel's internal implementation.