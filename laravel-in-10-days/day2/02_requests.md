# Requests

## Overview
Laravel's **Request** object contains information about the current HTTP request sent by the client.

It can be used to access:
* Form input
* Query parameters
* Route parameters
* Request method
* Headers
* Cookies
* Uploaded files
* JSON data

Client
   ↓
HTTP Request
   ↓
Laravel Request
   ↓
Controller

---

## 1. Using the Request Object

Import `Request`:

```php
use Illuminate\Http\Request;
```

Inject it into a controller method:

```php
public function store(Request $request)
{
    return $request;
}
```

Laravel's service container automatically provides the current request object.

---

## 2. Getting Input Data

Suppose the form sends:
name = Aman
email = aman@example.com

You can access the values:
$name = $request->input('name');
$email = $request->input('email');

You can also use:
$name = $request->name;

For example:

```php
public function store(Request $request)
{
    $name = $request->input('name');
    return $name;
}
```

---

## 3. Default Values

You can provide a default value:

```php
$name = $request->input('name', 'Guest');
```

If `name` is missing, Laravel returns: Guest

---

## 4. Getting Multiple Inputs

Get selected values:

```php
$data = $request->only(['name', 'email']);
```

Ignore selected values:

```php
$data = $request->except(['password']);
```

Get all input:

```php
$data = $request->all();
```

Be careful with `all()` when working with user input. Usually, validated data is safer for storing data.

---

## 5. Checking Whether Input Exists

Check if a value exists:

```php
if ($request->has('email')) {
    // email exists
}
```

Check if multiple values exist:
```php
$request->has(['name', 'email']);
```

Check if a value exists and is not empty:
$request->filled('email');

Check if a value is missing:
$request->missing('email');

---

## 6. Query Parameters

For a URL:
/users?page=2&search=aman

Access query parameters:

```php
$page = $request->query('page');
$search = $request->query('search');
```

You can also use:
```php
$request->page;
```

But `query()` clearly indicates that you are reading URL query parameters.

---

## 7. Route Parameters

Route:

```php
Route::get('/users/{id}', [UserController::class, 'show']);
```

URL:
/users/10

Access the route parameter:

```php
public function show(Request $request)
{
    $id = $request->route('id');
    return $id;
}
```

You can also receive it directly:

```php
public function show($id)
{
    return $id;
}
```

---

## 8. Request Method

Get the HTTP method:
$method = $request->method();

Examples:
GET
POST
PUT
PATCH
DELETE

Check a method:

```php
if ($request->isMethod('post')) {
    // POST request
}
```

---

## 9. Request Headers

Get a header:

```php
$token = $request->header('Authorization');
```

Check whether a header exists:

```php
if ($request->hasHeader('Authorization')) {
    // Header exists
}
```

---

## 10. JSON Requests

If the client sends JSON:

```json
{
    "name": "Aman",
    "email": "aman@example.com"
}
```

Laravel can access it through the Request object:

```php
$name = $request->input('name');
$email = $request->input('email');
```

You can also use:

```php
$data = $request->json()->all();
```

---

## 11. Uploaded Files

Check whether a file was uploaded:

```php
if ($request->hasFile('image')) {
    // File exists
}
```

Get the uploaded file:

```php
$image = $request->file('image');
```

Store it:

```php
$path = $request->file('image')->store('images');
```

File validation is normally handled through Laravel validation rules.

---

## 12. Cookies

Get a cookie:

```php
$value = $request->cookie('theme');
```

You can also check whether a cookie exists:

```php
$request->hasCookie('theme');
```

---

## 13. Request URL and Path

Get the full URL:

```php
$request->url();
```

Get the URL including query parameters:

```php
$request->fullUrl();
```

Get the current path:

```php
$request->path();
```

For:
https://example.com/users?page=2

Results include:
url()      → https://example.com/users
fullUrl()  → https://example.com/users?page=2
path()     → users

---

## 14. Request Input vs Validated Input

You can get raw input:

```php
$name = $request->input('name');
```

But after validation, prefer validated data:

```php
$data = $request->validated();
```

For example:

```php
public function store(StoreUserRequest $request)
{
    $data = $request->validated();

    User::create($data);
}
```

This ensures only data that passed your validation rules is used.

---

## 15. Request Flow in a Controller

A typical controller might look like:

```php
public function store(Request $request)
{
    $data = $request->only([
        'name',
        'email'
    ]);

    // Validate
    // Process
    // Save

    return redirect()->route('users.index');
}
```

In a real Laravel application, validation is commonly separated into a **Form Request**:

```php
public function store(StoreUserRequest $request)
{
    $data = $request->validated();
    User::create($data);
    return redirect()->route('users.index');
}
```

---

## Interview Points

**Q: What is the Request object?**
It represents the current HTTP request and provides access to input, route parameters, query parameters, headers, files, cookies, and other request information.

**Q: How do you get form input?**
```php
$request->input('name');
```

**Q: Difference between `input()` and `query()`?**
`input()` can retrieve request input generally, while `query()` specifically retrieves URL query parameters.

**Q: How do you get a route parameter?**
```php
$request->route('id');
```

**Q: How do you get uploaded files?**
```php
$request->file('image');
```

**Q: Why use `validated()` instead of `all()`?**
`validated()` returns only data that has passed the application's validation rules, making it safer to pass into application logic or database operations.