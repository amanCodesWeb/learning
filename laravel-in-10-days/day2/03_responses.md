# Responses

## Overview
A **Response** is what Laravel sends back to the client after processing a request.

Client
   ↓
Request
   ↓
Route → Controller
   ↓
Response
   ↓
Client


A response can be:
* HTML/View
* JSON
* Redirect
* File
* Download
* Plain text
* Error response

---

## 1. String Response

A controller can directly return a string:

```php
public function index()
{
    return "Hello World";
}
```

Laravel converts it into an HTTP response.

---

## 2. View Response

Return a Blade view:

```php
public function index()
{
    return view('users.index');
}
```

Pass data to the view:

```php
public function index()
{
    $users = User::all();

    return view('users.index', [
        'users' => $users
    ]);
}
```

---

## 3. JSON Response

For APIs, return JSON:

```php
public function show(User $user)
{
    return response()->json($user);
}
```

Custom JSON:

```php
return response()->json([
    'message' => 'User created successfully',
    'user' => $user
]);
```

---

## 4. HTTP Status Codes

You can specify the status code:

```php
return response()->json([
    'message' => 'User created'
], 201);
```

Common status codes:

| Code | Meaning          |
| ---- | ---------------- |
| 200  | OK               |
| 201  | Created          |
| 204  | No Content       |
| 400  | Bad Request      |
| 401  | Unauthenticated  |
| 403  | Forbidden        |
| 404  | Not Found        |
| 422  | Validation Error |
| 500  | Server Error     |

Example:

```php
return response()->json([
    'message' => 'User not found'
], 404);
```

---

## 5. Response Headers

Add headers to a response:

```php
return response()
    ->json(['message' => 'Success'])
    ->header('X-Custom-Header', 'Laravel');
```

Multiple headers:

```php
return response()
    ->json(['message' => 'Success'])
    ->withHeaders([
        'X-App-Version' => '1.0',
        'X-Custom-Header' => 'Laravel'
    ]);
```

---

## 6. Redirect Response

Redirect the user to another URL:

```php
return redirect('/users');
```

Redirect to a named route:

```php
return redirect()->route('users.index');
```

Redirect back:

```php
return redirect()->back();
```

---

## 7. Redirect with Data

You can send flash data with a redirect:

```php
return redirect()
    ->route('users.index')
    ->with('success', 'User created successfully.');
```

In Blade:

```php
@if (session('success'))
    {{ session('success') }}
@endif
```

This data is normally available for the next request.

---

## 8. Redirect with Input

When redirecting back after an error, you can preserve the submitted input:

```php
return redirect()
    ->back()
    ->withInput();
```

The previous input can then be accessed using:

```php
old('name')
```

Example:

```html
<input type="text" name="name" value="{{ old('name') }}">
```

---

## 9. File Download Response

Return a file as a download:

```php
return response()->download(
    storage_path('app/report.pdf')
);
```

Custom download filename:

```php
return response()->download(
    storage_path('app/report.pdf'),
    'user-report.pdf'
);
```

---

## 10. File Response

Display a file instead of downloading it:

```php
return response()->file(
    storage_path('app/report.pdf')
);
```
The browser decides how to display the file based on its content type.

---

## 11. Empty Response

Sometimes you need to return a response without content:

```php
return response()->noContent();
```

This returns:
204 No Content

Commonly used when an API operation succeeds but there is nothing to return.

---

## 12. Response vs Redirect

These are different:

### Response

```php
return response()->json([
    'message' => 'Success'
]);
```

The server sends data directly back to the client.

### Redirect

```php
return redirect()->route('users.index');
```

The server tells the browser to make another request to the specified URL.

```text
Response:

Client → Server
        ← Data


Redirect:

Client → Server
        ← Redirect
Client → New URL
        ← New Response
```

---

## 13. API Response Example

A typical API controller:

```php
public function store(StoreUserRequest $request)
{
    $user = User::create($request->validated());

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user
    ], 201);
}
```

Here:

1. Request data is validated.
2. User is created.
3. Laravel returns JSON.
4. HTTP status `201` indicates the resource was created.

---

## Interview Points

**Q: What is a response in Laravel?**

A response is the data Laravel sends back to the client after processing an HTTP request.

**Q: How do you return JSON?**

```php
return response()->json([
    'message' => 'Success'
]);
```

**Q: How do you return a specific HTTP status code?**

```php
return response()->json([
    'message' => 'Created'
], 201);
```

**Q: Difference between `response()` and `redirect()`?**

`response()` creates a response sent directly to the client, while `redirect()` tells the client to make another request to a different URL.

**Q: How do you redirect to a named route?**

```php
return redirect()->route('users.index');
```

**Q: How do you return a file for download?**

```php
return response()->download($path);
```
