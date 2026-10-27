# Controllers

## Overview

A **Controller** handles the application logic between a **Route** and the **View/Response**.
Instead of putting a lot of logic inside routes, we move it into controller methods.

Request
   ↓
Route
   ↓
Controller
   ↓
Model / Service
   ↓
Response / View / JSON

---

## 1. Creating a Controller

Use Artisan:
php artisan make:controller UserController

This creates:
app/Http/Controllers/UserController.php

Basic controller:

```php
<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        return "Users list";
    }
}
```

---

## 2. Connecting a Route to a Controller

```php
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);
```

When `/users` is requested, Laravel calls:

```php
UserController::index()
```

---

## 3. Controller Methods

A controller can contain multiple methods:

```php
class UserController extends Controller
{
    public function index()
    {
        return "All users";
    }

    public function show($id)
    {
        return "User: " . $id;
    }

    public function create()
    {
        return view('users.create');
    }

    public function store()
    {
        return "Store user";
    }
}
```

Each method normally represents an action.

---

## 4. Passing Data to a View

```php
public function index()
{
    $users = User::all();
    return view('users.index', compact('users'));
}
```

Or:

```php
return view('users.index', [
    'users' => $users
]);
```

The data can then be accessed in the Blade view:

```php
@foreach ($users as $user)
    {{ $user->name }}
@endforeach
```

---

## 5. Route Parameters in Controllers

Route:

```php
Route::get('/users/{id}', [UserController::class, 'show']);
```

Controller:

```php
public function show($id)
{
    return "User ID: " . $id;
}
```

Request:
/users/10

Output:
User ID: 10

---

## 6. Route Model Binding

Laravel can automatically resolve a model from a route parameter.

```php
use App\Models\User;

Route::get('/users/{user}', [UserController::class, 'show']);
```

Controller:

```php
public function show(User $user)
{
    return $user;
}
```

Laravel automatically finds the `User` with the matching ID.
If the user does not exist, Laravel automatically returns a `404` response.

---

## 7. Resource Controllers

Resource controllers are useful for CRUD operations.

Create one:
php artisan make:controller UserController --resource

Laravel creates these conventional methods:

```php
index()    // List resources
create()   // Show create form
store()    // Store new resource
show()     // Show one resource
edit()     // Show edit form
update()   // Update resource
destroy()  // Delete resource
```

---

## 8. Resource Routes

Instead of defining every CRUD route manually:

```php
Route::resource('users', UserController::class);
```

Laravel automatically creates the standard CRUD routes.

| HTTP      | URL                  | Method      | Purpose     |
| --------- | -------------------- | ----------- | ----------- |
| GET       | `/users`             | `index()`   | List users  |
| GET       | `/users/create`      | `create()`  | Create form |
| POST      | `/users`             | `store()`   | Store user  |
| GET       | `/users/{user}`      | `show()`    | Show user   |
| GET       | `/users/{user}/edit` | `edit()`    | Edit form   |
| PUT/PATCH | `/users/{user}`      | `update()`  | Update user |
| DELETE    | `/users/{user}`      | `destroy()` | Delete user |

---

## 9. Limiting Resource Routes

Only specific actions:

```php
Route::resource('users', UserController::class)
    ->only(['index', 'show']);
```

Exclude actions:

```php
Route::resource('users', UserController::class)
    ->except(['destroy']);
```

---

## 10. Dependency Injection in Controllers

Laravel's service container can automatically inject dependencies.

```php
class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
}
```

Then:

```php
public function index()
{
    $users = $this->userService->getUsers();
    return view('users.index', compact('users'));
}
```

This keeps controllers from containing too much business logic.

---

## 11. Controllers Should Stay Focused

Avoid putting large business logic directly inside controllers.

Bad:

```php
public function store(Request $request)
{
    // 100+ lines of business logic
}
```

Better:

```php
public function store(StoreUserRequest $request)
{
    $user = $this->userService->create($request->validated());
    return redirect()->route('users.show', $user);
}
```

A common architecture is:

Controller
    ↓
Request Validation
    ↓
Service
    ↓
Model / Repository
    ↓
Database

---

## 12. Useful Artisan Commands

Create a basic controller:
php artisan make:controller UserController

Create a resource controller:
php artisan make:controller UserController --resource

Create a resource controller with model binding:
php artisan make:controller UserController --resource --model=User

---

## Interview Points

**Q: What is a controller?**
A controller handles application logic between routes and the application's models, views, or other responses.

**Q: Why use controllers instead of putting logic in routes?**
Controllers keep routes clean and organize application logic into reusable, maintainable classes.

**Q: What is a resource controller?**
A controller designed around standard CRUD operations such as `index`, `store`, `show`, `update`, and `destroy`.

**Q: What does `Route::resource()` do?**
It automatically registers the conventional CRUD routes for a resource controller.

**Q: What is route model binding?**
It allows Laravel to automatically resolve a route parameter into a model instance.

**Q: Should business logic be inside controllers?**
Controllers should generally coordinate the request and response rather than contain large amounts of business logic. Complex logic can be moved into services or other appropriate classes.
