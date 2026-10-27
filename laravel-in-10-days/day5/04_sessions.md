# Sessions

## Overview

A **session** stores information about a user across multiple HTTP requests.

HTTP is stateless, meaning each request is independent. Laravel sessions allow the application to remember data between requests.

Example:

    Request 1
        ↓
    User logs in
        ↓
    Session stores authentication state
        ↓
    Request 2
        ↓
    Laravel identifies the user

---

## Basic Session Usage

Laravel provides the `session()` helper and `Request` methods.

### Store Data
    session(['name' => 'Aman']);

Or:
    $request->session()->put('name', 'Aman');

### Get Data
    $name = session('name');

Or:
    $name = $request->session()->get('name');

### Check Data
    $request->session()->has('name');

### Remove Data
    $request->session()->forget('name');

### Remove All Session Data
    $request->session()->flush();

---

## Flash Data

Flash data is available only for the current request and the next request.

Useful for:
- Success messages
- Error messages
- Temporary notifications

Example:

    $request->session()->flash(
        'success',
        'Order created successfully.'
    );

Retrieve it:
    $message = session('success');

Example:

    return redirect('/orders')
        ->with('success', 'Order created successfully.');

---

## Session Regeneration

Laravel can regenerate the session ID:
    $request->session()->regenerate();

This is commonly done after login to help prevent **session fixation attacks**.

Example:

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect('/dashboard');
    }

---

## Session Invalidation

When logging out, invalidate the session:

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

This removes the current session data and creates a new CSRF token.

---

## Session Driver

Laravel supports different session storage drivers.

Common drivers include:

    file
    database
    cookie
    redis
    memcached

The session driver is configured using:
    SESSION_DRIVER

Example `.env`:

    SESSION_DRIVER=file

The application can therefore change how session data is stored without changing application logic.

---

## File Sessions

With the `file` driver, session data is stored on the server.

Typical location:
    storage/framework/sessions

This is simple and suitable for many applications.

---

## Database Sessions

Laravel can store sessions in a database.

Example:
    SESSION_DRIVER=database

The application uses a sessions table to store session data.
This can be useful when multiple application servers need shared session storage.

---

## Session Cookie

The browser normally stores a **session cookie** containing the session identifier.

Simplified flow:

    Browser
       ↓
    Session Cookie
       ↓
    Laravel
       ↓
    Session Storage
       ↓
    User Session Data

The actual session data does not necessarily need to be stored inside the browser.

---

## Session Configuration

Session configuration is mainly stored in:

    config/session.php

Important settings include:

- Session driver
- Session lifetime
- Cookie name
- Cookie domain
- Secure cookies
- HTTP-only cookies

Example:

    'lifetime' => 120,

This controls how long the session can remain valid based on Laravel's session configuration.

---

## Session Data vs Cookies

| Session | Cookie |
|---|---|
| Usually stores data server-side | Stored in browser |
| Identified through a session cookie | Contains cookie data directly |
| Can store larger application state | Should remain small |
| Commonly used for authentication state | Commonly used for identifiers/preferences |

Laravel can also use a cookie-based session driver, where encrypted session data is stored in the cookie.

---

## Common Session Methods

| Method | Purpose |
|---|---|
| `session()->put()` | Store data |
| `session()->get()` | Retrieve data |
| `session()->has()` | Check if data exists |
| `session()->forget()` | Remove specific data |
| `session()->flush()` | Remove all session data |
| `session()->flash()` | Store temporary data |
| `session()->regenerate()` | Generate a new session ID |
| `session()->invalidate()` | Destroy the current session |

---

## Interview Questions

### What is a session?

A session allows an application to maintain user-specific data across multiple HTTP requests.

### Why are sessions needed?

HTTP is stateless, so sessions provide a way to maintain state between requests.

### What is flash data?

Temporary session data that is normally available for the current request and the next request.

### Why regenerate the session after login?

To generate a new session ID and help prevent session fixation attacks.

### Where is session configuration stored?

    config/session.php

### What is a session driver?

A session driver defines **where and how Laravel stores session data**, such as files, database, Redis, or cookies.

### What happens when a session is invalidated?

The current session is destroyed and the user receives a new session context.

---

## Key Takeaways

- Sessions maintain data across HTTP requests.
- Laravel provides `session()` and `$request->session()` for session management.
- Sessions are commonly used for **web authentication**.
- Flash data is useful for temporary messages.
- Regenerate the session after login.
- Invalidate the session during logout.
- The session driver determines where session data is stored.
- Session configuration is mainly defined in `config/session.php`.