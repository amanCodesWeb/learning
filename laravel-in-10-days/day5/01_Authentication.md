# Authentication

## Overview

**Authentication** verifies the identity of a user.

Example:

    Login Form
        ↓
    Email + Password
        ↓
    Laravel verifies credentials
        ↓
    User authenticated

### Authentication vs Authorization

| Authentication | Authorization |
|
| Who are you? | What can you access? |
| Verifies identity | Checks permissions |

---

## Laravel Authentication Flow

    Login Request
        ↓
    Validate credentials
        ↓
    Auth::attempt()
        ↓
    Credentials valid?
       ↙   ↘
     No     Yes
     ↓       ↓
    Error   Session created
                ↓
          Authenticated User

Laravel commonly uses **session-based authentication** for web applications.

---

## `Auth::attempt()`

Used to verify credentials and authenticate a user.

    use Illuminate\Support\Facades\Auth;

    if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
    ])) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ]);

`Auth::attempt()` returns `true` when authentication succeeds and `false` otherwise.

---

## Checking the Authenticated User

### Check Authentication

    Auth::check();

Returns `true` if a user is authenticated.

### Get Current User

    $user = Auth::user();

### Get User ID

    $id = Auth::id();

---

## Protecting Routes

Use the `auth` middleware to allow only authenticated users.

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('auth');

For multiple routes:

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/profile', [ProfileController::class, 'index']);
    });

---

## Logout

    Auth::logout();

For session-based authentication:

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');

This removes the authenticated session and regenerates the CSRF token.

---

## Password Hashing

Passwords should **never be stored as plain text**.

Create a hash:

    use Illuminate\Support\Facades\Hash;

    $hash = Hash::make('secret123');

Verify a password:

    Hash::check('secret123', $hash);

When using `Auth::attempt()`, Laravel handles password verification automatically.

---

## Guards

A **guard** defines how Laravel authenticates a user.

For example, the `web` guard uses sessions:

    Auth::guard('web')->user();

Authentication configuration is mainly defined in:

    config/auth.php

Example:

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

### Guard vs Provider

    Guard
      ↓
    How to authenticate
      ↓
    Provider
      ↓
    Where to retrieve the user

A provider commonly retrieves users through the Eloquent `User` model.

---

## `Auth::attempt()` vs `Auth::login()`

### `attempt()`

Verifies credentials before authentication:

    Auth::attempt([
        'email' => $email,
        'password' => $password
    ]);

### `login()`

Authenticates an already retrieved user:

    $user = User::find(1);
    Auth::login($user);

---

## Session Regeneration

After successful login:

    $request->session()->regenerate();

This changes the session ID and helps protect against **session fixation**.

A common login pattern is:

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

`redirect()->intended()` sends the user back to the page they originally tried to access.

---

## Remember Me

Laravel supports persistent login through the second argument of `Auth::attempt()`:

    $remember = $request->boolean('remember');
    Auth::attempt($credentials, $remember);

---

## Important Methods

| Method | Purpose |
|---|---|
| `Auth::attempt()` | Verify credentials and log in |
| `Auth::check()` | Check authentication status |
| `Auth::user()` | Get authenticated user |
| `Auth::id()` | Get authenticated user ID |
| `Auth::login()` | Log in an existing user |
| `Auth::logout()` | Log out user |
| `Auth::guard()` | Use a specific guard |

---

## Interview Questions

### What is authentication?

Authentication verifies the identity of a user.

### What is the difference between authentication and authorization?

Authentication verifies **who the user is**, while authorization determines **what the user is allowed to do**.

### What does `Auth::attempt()` do?

It verifies the supplied credentials and authenticates the user if they are valid.

### Why regenerate the session after login?

To change the session ID and help prevent session fixation attacks.

### What is a guard?

A guard defines **how Laravel authenticates users**.

### What is a provider?

A provider defines **how Laravel retrieves users**.

### Where is authentication configuration stored?

    config/auth.php

---

## Key Takeaways

- Authentication = **verify user identity**.
- Laravel commonly uses **session-based authentication** for web applications.
- `Auth::attempt()` is the common credential-based login method.
- `auth` middleware protects routes.
- `Auth::user()` returns the logged-in user.
- Passwords must be **hashed**, never stored as plain text.
- **Guards** define how authentication works.
- **Providers** define how users are retrieved.
- Regenerate the session after successful login.