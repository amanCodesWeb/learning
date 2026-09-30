# Laravel Sanctum

## Overview

**Laravel Sanctum** provides lightweight authentication for:
- SPA applications
- Mobile applications
- API authentication
- Simple token-based APIs

Sanctum supports two main approaches:

    SPA Authentication
        → Cookie + Session

    API Authentication
        → Personal Access Token

---

## Installing Sanctum

In modern Laravel applications, Sanctum can be installed using:

    php artisan install:api

This sets up the API authentication infrastructure required by the application.

---

## Sanctum Token Authentication

For mobile apps or external clients, Sanctum can issue API tokens.

The `User` model uses the `HasApiTokens` trait:

    use Laravel\Sanctum\HasApiTokens;

    class User extends Authenticatable
    {
        use HasApiTokens;
    }

Create a token:

    $token = $user->createToken('mobile-app')->plainTextToken;

The token can then be returned to the client:

    return response()->json([
        'token' => $token
    ]);

---

## Sending the Token

The client sends the token using the `Authorization` header:

    Authorization: Bearer YOUR_TOKEN

Example request:

    GET /api/user
    Authorization: Bearer 1|xxxxxxxxxxxxxxxx

Sanctum reads the token and identifies the authenticated user.

---

## Protecting API Routes

Use the `auth:sanctum` middleware:

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });

Only authenticated Sanctum users can access these routes.

---

## Getting the Authenticated User

Inside a protected route or controller:

    $user = $request->user();

Example:

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

---

## Revoking Tokens

A user can revoke the current token:
    $request->user()->currentAccessToken()->delete();

To revoke all tokens:
    $request->user()->tokens()->delete();

This is useful for logout or when a token should no longer be trusted.

---

## Token Abilities

Sanctum allows tokens to have specific abilities.

Create a token:

    $token = $user->createToken(
        'mobile-app',
        ['orders:read', 'orders:create']
    )->plainTextToken;

Check an ability:

    if ($request->user()->tokenCan('orders:create')) {
        // User can create orders
    }

This allows different tokens to have different permissions.

---

## Sanctum vs Session Authentication

| Session Authentication | Sanctum Token Authentication |
|---
| Common for web applications | Common for APIs/mobile apps |
| Uses sessions and cookies | Uses API tokens |
| Browser-focused | Client/API-focused |
| `auth` middleware | `auth:sanctum` middleware |

Sanctum can also authenticate first-party SPAs using Laravel's session-based authentication and cookies.

---

## Sanctum SPA Authentication

For a first-party SPA, Sanctum can use Laravel's normal session authentication instead of API tokens.

Typical flow:

    SPA
      ↓
    /sanctum/csrf-cookie
      ↓
    Login
      ↓
    Session Cookie
      ↓
    API Requests
      ↓
    Laravel authenticates user

This approach is useful when the SPA and Laravel backend are part of the same application ecosystem.

---

## Sanctum vs Passport

| Sanctum | Passport |
|---
| Lightweight | More full-featured |
| Simple API tokens | OAuth2 implementation |
| SPA authentication | OAuth2 clients/server |
| Mobile/API authentication | Complex authorization scenarios |

Use Sanctum when you need simple API authentication.
Passport is more appropriate when your application specifically requires OAuth2 features.

---

## Typical API Login

Example:

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

The client then uses the returned token for protected API requests.

---

## Authentication Flow

    Login
      ↓
    Validate Credentials
      ↓
    User Verified
      ↓
    createToken()
      ↓
    Token Returned
      ↓
    Client Stores Token
      ↓
    Authorization: Bearer TOKEN
      ↓
    auth:sanctum
      ↓
    Protected API

---

## Important Methods

| Method | Purpose |
|---|---|
| `createToken()` | Create an API token |
| `plainTextToken` | Get the newly created token |
| `currentAccessToken()` | Get the current token |
| `tokenCan()` | Check token ability |
| `tokens()->delete()` | Revoke user's tokens |
| `auth:sanctum` | Protect API routes |

---

## Interview Questions

### What is Laravel Sanctum?

Sanctum is Laravel's lightweight authentication system for SPAs, APIs, and mobile applications.

### How does Sanctum authenticate API requests?

The client sends a Sanctum token using the `Authorization: Bearer TOKEN` header.

### How do you protect a Sanctum API route?

    Route::middleware('auth:sanctum')->get('/profile', ...);

### How do you create a Sanctum token?

    $token = $user->createToken('api-token')->plainTextToken;

### How do you revoke a token?

    $request->user()->currentAccessToken()->delete();

### Sanctum vs Passport?

Sanctum is designed for lightweight authentication, while Passport provides a full OAuth2 implementation.

---

## Key Takeaways

- Sanctum provides lightweight authentication for **APIs, SPAs, and mobile apps**.
- API tokens are created using `createToken()`.
- Protected API routes use `auth:sanctum`.
- Tokens are normally sent as `Bearer` tokens.
- Sanctum supports **token abilities**.
- Tokens can be revoked individually or in bulk.
- Sanctum can also authenticate first-party SPAs using cookies and sessions.
- Use Sanctum for simple API authentication; use Passport when OAuth2 is specifically required.