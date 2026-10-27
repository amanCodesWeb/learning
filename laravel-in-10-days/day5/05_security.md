# 5.5 Security

## Overview

Laravel provides several built-in features to protect applications from common security threats.

Important areas include:

- CSRF protection
- Password hashing
- SQL injection prevention
- XSS protection
- Authentication
- Authorization
- Secure sessions
- Input validation

---

## CSRF Protection

**CSRF (Cross-Site Request Forgery)** tricks an authenticated user into sending an unwanted request.

Laravel protects state-changing web requests using CSRF tokens.

In Blade forms:

    <form method="POST" action="/profile">
        @csrf

        <button type="submit">Update</button>
    </form>

Laravel verifies the token before processing the request.

For AJAX requests, the CSRF token can also be sent through the request headers.

---

## SQL Injection

SQL injection happens when untrusted input is directly inserted into SQL queries.

Avoid:

    DB::select(
        "SELECT * FROM users WHERE email = '$email'"
    );

Use Laravel's query builder or Eloquent:

    User::where('email', $email)->first();

Laravel uses parameter binding for these queries, which helps prevent SQL injection.

---

## Mass Assignment

Mass assignment can allow users to modify fields that should not be directly controlled by them.

Example:
    User::create($request->all());

If the request contains unexpected fields, they could potentially be assigned.

Use controlled input:

    User::create([
        'name' => $request->name,
        'email' => $request->email,
    ]);

Or configure the model using `$fillable` / `$guarded`.

Example:

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

---

## XSS Protection

**XSS (Cross-Site Scripting)** occurs when malicious JavaScript is injected into a page.

Blade automatically escapes normal output:
    {{ $user->name }}

This is safer than rendering raw HTML.

For trusted HTML, Blade provides:
    {!! $html !!}

Raw output should only be used when the content is trusted or properly sanitized.

---

## Input Validation

Always validate user input before processing it.

Example:

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8'],
    ]);

Validation helps ensure that application logic receives expected data.

---

## Password Security

Never store plain-text passwords.

Use Laravel's hashing system:

    use Illuminate\Support\Facades\Hash;

    $password = Hash::make($request->password);

Verify:

    Hash::check($password, $hashedPassword);

Laravel's authentication system also handles password verification when using `Auth::attempt()`.

---

## Environment Variables

Sensitive configuration should not be hard-coded into application code.

Example `.env`:

    APP_KEY=...
    DB_PASSWORD=...
    STRIPE_SECRET=...

Access configuration through Laravel's configuration system:

    config('services.stripe.secret');

Do not commit sensitive `.env` values to source control.

---

## `APP_KEY`

Laravel uses `APP_KEY` for encryption and other security-related operations.
It should be kept secret and should not be exposed publicly.

Generate it with:

    php artisan key:generate

Do not change the application key casually in an existing production application because previously encrypted data may no longer be decryptable.

---

## Secure Cookies

Authentication and session cookies should be configured securely.

Important cookie settings include:
- `Secure`
- `HttpOnly`
- `SameSite`

`Secure` ensures cookies are sent over HTTPS.

`HttpOnly` prevents JavaScript from directly reading the cookie.

`SameSite` helps control cross-site cookie sending.

Laravel provides configuration for these settings through its session configuration.

---

## HTTPS

Production applications should use **HTTPS**.

HTTPS protects data while it travels between the client and server.

Especially important for:

- Login credentials
- Session cookies
- API tokens
- Payment information
- Personal data

---

## Authentication and Authorization

Security also depends on controlling access.

Authentication:

    Who is the user?

Authorization:

    What is the user allowed to do?

Example:

    Route::middleware('auth')->group(function () {
        // Authenticated users only
    });

Authorization can then be handled with Gates or Policies.

---

## Rate Limiting

Rate limiting restricts how frequently a user can access an endpoint.

It helps protect APIs and sensitive endpoints from excessive requests.

Example:

    Route::middleware('throttle:60,1')
        ->get('/api/products', [ProductController::class, 'index']);

This limits the route to approximately 60 requests per minute per rate-limit key.

---

## Security Checklist

    Validate input
        ↓
    Use Eloquent / Query Builder
        ↓
    Hash passwords
        ↓
    Protect forms with CSRF
        ↓
    Escape output
        ↓
    Protect routes with authentication
        ↓
    Check authorization
        ↓
    Use HTTPS
        ↓
    Protect secrets in .env
        ↓
    Apply rate limits where needed

---

## Interview Questions

### What is CSRF?

CSRF is an attack where a malicious site attempts to make an authenticated user's browser perform an unwanted action.

### How does Laravel protect against CSRF?

Laravel generates and validates CSRF tokens for protected web requests.

### What is SQL injection?

SQL injection occurs when malicious input is used to manipulate SQL queries.

Using Eloquent, Query Builder, and parameter binding helps prevent it.

### What is XSS?

XSS allows malicious scripts to be injected into web pages.

Blade's `{{ }}` syntax automatically escapes output.

### Why should `$request->all()` be avoided for mass assignment?

It may contain fields that the user should not be allowed to modify.

Use validation and controlled fields instead.

### Why should `.env` values not be committed?

The `.env` file can contain sensitive credentials such as database passwords and API secrets.

### Why is HTTPS important?

HTTPS encrypts data transmitted between the client and server and helps protect credentials, cookies, tokens, and other sensitive information.

---

## Key Takeaways

- Use **CSRF protection** for state-changing web requests.
- Validate all user input.
- Use Eloquent or parameterized queries to prevent SQL injection.
- Escape output to reduce XSS risks.
- Protect against mass assignment with `$fillable` or controlled input.
- Hash passwords with Laravel's `Hash` system.
- Keep secrets in `.env` and never commit them.
- Keep `APP_KEY` secret.
- Use HTTPS in production.
- Protect routes with authentication and authorization.
- Use rate limiting for sensitive or high-volume endpoints.