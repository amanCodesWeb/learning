# Authorization

## Overview

**Authorization** determines what an authenticated user is **allowed to do**.

Example:

    User logs in
        ↓
    Authentication
        ↓
    Is user allowed to edit this product?
        ↓
    Authorization

### Authentication vs Authorization

| Authentication | Authorization |
|---|---|
| Verifies who the user is | Checks what they can do |
| Happens during login | Happens when accessing a resource/action |
| Example: Login | Example: Edit Product |

---

## Gates

A **Gate** is a simple way to define an authorization rule.
Gates are useful for actions that are not necessarily tied to a specific model.

Example:

    use Illuminate\Support\Facades\Gate;

    Gate::define('access-admin', function ($user) {
        return $user->is_admin;
    });

Check the gate:

    if (Gate::allows('access-admin')) {
        // User can access
    }

Or:

    if (Gate::denies('access-admin')) {
        // User is not allowed
    }

---

## Gate in Routes

Authorization can be applied directly to routes:

    Route::get('/admin', function () {
        return view('admin');
    })->middleware('auth');

The `auth` middleware checks authentication, while authorization should check whether the user has the required permission.

---

## Policies

A **Policy** is a class that organizes authorization logic for a specific model or resource.

Example:

    php artisan make:policy PostPolicy --model=Post

A policy might contain:

    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }

This means a user can update a post only if they own it.

---

## Using a Policy

A controller can authorize an action:

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        // Update post...
    }

If the user is not authorized, Laravel throws an authorization exception and returns a `403 Forbidden` response.

---

## Policy Methods

Common policy methods include:

    view()
    create()
    update()
    delete()
    restore()
    forceDelete()

Example:

    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }

---

## `can` Middleware

Laravel provides the `can` middleware for authorization.

    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->middleware(['auth', 'can:update,post']);

Flow:

    Request
      ↓
    auth middleware
      ↓
    User authenticated?
      ↓
    can middleware
      ↓
    User authorized?
      ↓
    Controller

---

## Blade Authorization

Authorization checks can also be used in Blade views.

    @can('update', $post)
        <a href="/posts/{{ $post->id }}/edit">
            Edit
        </a>
    @endcan

You can also use:

    @cannot('delete', $post)
        <p>You cannot delete this post.</p>
    @endcannot

This is useful for hiding UI elements the user cannot use.

---

## Roles and Permissions

Authorization is often based on roles or permissions.

Example:

    Admin
      → Create
      → Read
      → Update
      → Delete

    Editor
      → Create
      → Read
      → Update

    User
      → Read

Laravel's Gates and Policies can implement these rules.

For complex permission systems, applications often use a dedicated roles/permissions package or their own permission layer.

---

## Gate vs Policy

| Gate | Policy |
|---|---|
| General authorization rule | Model/resource-specific rules |
| Good for simple actions | Good for CRUD authorization |
| Defined with `Gate::define()` | Defined inside a Policy class |
| Example: access admin panel | Example: update a Post |

Simple rule:

    Gate
    → "Can this user perform this general action?"

    Policy
    → "Can this user perform this action on this model?"

---

## Authorization Flow

    Request
       ↓
    Authentication
       ↓
    User identified
       ↓
    Authorization
       ↓
    Gate / Policy
       ↓
    ┌──────────────┐
    │ Authorized?  │
    └──────────────┘
       ↓        ↓
      No        Yes
      ↓          ↓
    403       Controller
                ↓
              Action

---

## Important Methods

| Method | Purpose |
|---|---|
| `Gate::allows()` | Check if action is allowed |
| `Gate::denies()` | Check if action is denied |
| `$this->authorize()` | Authorize an action in a controller |
| `@can` | Authorization check in Blade |
| `@cannot` | Check that action is not allowed |
| `can` middleware | Protect routes with authorization |

---

## Interview Questions

### What is authorization?

Authorization determines whether an authenticated user has permission to perform a specific action.

### What is a Gate?

A Gate is a simple authorization rule, usually used for general actions.

### What is a Policy?

A Policy is a class that organizes authorization rules for a specific model or resource.

### When would you use a Policy instead of a Gate?

Use a Policy when authorization logic is closely related to a model or resource, such as deciding who can update or delete a particular post.

### What happens when `$this->authorize()` fails?

Laravel throws an authorization exception and returns a `403 Forbidden` response.

### Does `auth` middleware handle authorization?

No.

`auth` middleware handles **authentication**. Authorization is handled separately using Gates, Policies, permissions, or similar rules.

---

## Key Takeaways

- Authentication = **Who are you?**
- Authorization = **What are you allowed to do?**
- **Gates** are useful for general authorization rules.
- **Policies** organize authorization around models/resources.
- `$this->authorize()` can enforce authorization in controllers.
- `can` middleware can protect routes.
- `@can` can control authorized UI elements in Blade.
- Failed authorization normally results in **403 Forbidden**.