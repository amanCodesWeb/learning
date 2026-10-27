# Middleware

## Overview

**Middleware** filters HTTP requests before they reach the controller.

Common uses:
* Authentication
* Authorization
* Roles and permissions
* Request validation
* Logging
* Security checks
* Redirecting users
* Rate limiting

### Request Flow

Browser
   ↓
Route
   ↓
Middleware
   ↓
Controller
   ↓
Response

If middleware allows the request, it continues to the next middleware/controller.
If it rejects the request, it can return a response or redirect.

---

## Creating Middleware

```bash
php artisan make:middleware CheckAdmin
```

Creates:
app/Http/Middleware/CheckAdmin.php

---

## Basic Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()?->is_admin) {
            return redirect('/');
        }

        return $next($request);
    }
}
```

### `$next($request)`

```php
return $next($request);
```

Passes the request to the next middleware/controller.
If `$next()` is not called, the request stops at that middleware.

---

## Registering Middleware

In newer Laravel versions, middleware aliases can be configured in:

bootstrap/app.php

Example:

```php
->withMiddleware(function ($middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\CheckAdmin::class,
    ]);
})
```

The alias can then be used on routes:

```php
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('admin');
```

---

## Middleware on Routes

### Single Route

```php
Route::get('/profile', [ProfileController::class, 'index'])
    ->middleware('auth');
```

### Multiple Middleware

```php
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin']);
```

### Route Group

```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/users', [AdminController::class, 'users']);
});
```

---

## Global Middleware

**Global middleware** runs for every HTTP request.

Request
   ↓
Global Middleware
   ↓
Route Middleware
   ↓
Controller

Use global middleware when the logic should apply broadly across the application.

---

## Middleware Parameters

Middleware can receive parameters.

Route:

```php
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('role:admin');
```

Middleware:

```php
public function handle(Request $request, Closure $next, string $role)
{
    if ($request->user()?->role !== $role) {
        abort(403);
    }

    return $next($request);
}
```

Here:

```php
role:admin
    ↓
$role = "admin"
```

Multiple parameters can also be passed:

```php
->middleware('role:admin,manager');
```

---

## Before and After Middleware

Middleware can execute code both **before** and **after** the controller.

```php
public function handle(Request $request, Closure $next)
{
    // Before controller
    $response = $next($request);

    // After controller
    return $response;
}
```

Example:

```php
public function handle(Request $request, Closure $next)
{
    logger('Request started');

    $response = $next($request);

    logger('Request finished');

    return $response;
}
```

---

## Common Laravel Middleware

Common middleware aliases include:
auth
guest
verified
throttle

Example:

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');
```

Only authenticated users can access the route.

---

## Middleware vs Controller

| Middleware                        | Controller                       |
| --------------------------------- | -------------------------------- |
| Filters requests                  | Handles application logic        |
| Runs before the controller        | Processes the request            |
| Authentication/authorization      | Fetches and saves data           |
| Can reject or redirect requests   | Returns the application response |
| Can be applied to multiple routes | Usually handles specific routes  |

---

## Simple Example

Request
   ↓
auth middleware
   ↓
Is user logged in?
   ├── No → Login page
   │
   └── Yes
        ↓
   Controller
        ↓
   Dashboard

---

## Key Points

* Middleware sits between the request and controller.
* `$next($request)` passes the request forward.
* Middleware can allow, reject, redirect, or modify requests.
* Middleware can be applied to individual routes or route groups.
* Global middleware runs for every request.
* Middleware aliases make middleware easier to use.
* Middleware can accept parameters.
* Middleware can execute logic before and after the controller.
* `auth` middleware is commonly used to protect authenticated routes.

### Interview Definition

> **Middleware is a layer that filters or processes an incoming HTTP request before it reaches the controller and can also process the response after the controller executes.**
