# Validation

## Overview

**Validation** checks whether incoming request data meets defined rules before processing it.

Example:
User submits form
       ↓
Request data
       ↓
Validation
   ↓         ↓
Valid      Invalid
  ↓           ↓
Controller   Errors

Laravel provides built-in validation rules for common requirements such as:
* Required fields
* Strings and numbers
* Email addresses
* Passwords
* Dates
* Files
* Unique values
* Database relationships

---

## Basic Validation

Validation can be performed directly on the request:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email',
        'age' => 'required|integer|min:18',
    ]);

    // Use validated data
}
```

If validation fails, Laravel automatically redirects back with validation errors for a normal web request.

---

## Validation Rules

Rules are separated by `|`:

```php
$request->validate([
    'name' => 'required|string|max:100',
    'email' => 'required|email',
    'age' => 'integer|min:18|max:100',
]);
```

Common rules:

required
nullable
string
integer
numeric
boolean
array
email
url
date
min
max
between
in
not_in
unique
exists
confirmed
same
different

---

## Required vs Nullable

### Required

```php
'name' => 'required|string'
```

The field must be present and cannot be empty.

### Nullable

```php
'phone' => 'nullable|string'
```
The field can be `null`, but if a value is provided, it must be a string.

---

## Database Validation

### Unique

Ensures a value does not already exist:

```php
'email' => 'required|email|unique:users,email'
```

### Exists

Ensures a value exists in a database table:

```php
'user_id' => 'required|exists:users,id'
```

Example:

```php
$request->validate([
    'category_id' => 'required|exists:categories,id',
]);
```

---

## Validation Errors

Laravel provides validation errors through the `$errors` variable in Blade.

```blade
@error('email')
    <span>{{ $message }}</span>
@enderror
```

Check if any validation errors exist:

```blade
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
```

---

## Old Input

After validation fails, Laravel can retain the submitted input.

```blade
<input type="text" name="name" value="{{ old('name') }}">
```

For a default value:

```blade
value="{{ old('name', $user->name) }}"
```

---

## Custom Validation Messages

Custom messages can be provided as the second argument:

```php
$request->validate(
    [
        'email' => 'required|email',
        'age' => 'required|integer|min:18',
    ],
    [
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email.',
        'age.min' => 'You must be at least 18 years old.',
    ]
);
```

---

## Custom Attribute Names

The third argument can define friendly attribute names:

```php
$request->validate(
    [
        'user_email' => 'required|email',
    ],
    [],
    [
        'user_email' => 'email address',
    ]
);
```

This makes validation messages more readable.

---

## Validation with Separate Rule Syntax

Rules can also be written as an array:

```php
$request->validate([
    'name' => ['required', 'string', 'max:100'],
    'email' => ['required', 'email'],
    'age' => ['required', 'integer', 'min:18'],
]);
```

This is useful when validation rules become more complex.

---

## Form Request Validation

For larger applications, validation can be moved into a **Form Request** class.

Create one:
php artisan make:request StoreUserRequest

Example:

```php
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ];
    }
}
```

Use it in the controller:

```php
public function store(StoreUserRequest $request)
{
    $validated = $request->validated();

    // Store user
}
```

### Why Form Requests?

They keep controllers cleaner by separating validation logic from business logic.

Controller
    ↓
Form Request
    ↓
Validation Rules

---

## Conditional Validation

Sometimes a field is required only when another field has a certain value.

Example:

```php
$request->validate([
    'type' => 'required|in:company,individual',
    'company_name' => 'required_if:type,company',
]);
```

Here `company_name` is required only when:

type = company

---

## API Validation

For APIs, validation errors are normally returned as a JSON response.

Example request:

```json
{
    "email": "wrong-value"
}
```

Validation:

```php
$request->validate([
    'email' => 'required|email',
]);
```

The API can return validation errors such as:

```json
{
    "message": "The email field must be a valid email address.",
    "errors": {
        "email": [
            "The email field must be a valid email address."
        ]
    }
}
```

---

## Validation vs Sanitization

**Validation** checks whether data is acceptable.

email = "abc"
    ↓
Validation
    ↓
Invalid

**Sanitization/transformation** modifies or prepares data.

"  Aman  "
     ↓
trim()
     ↓
"Aman"

Validation does not automatically mean that input has been sanitized.

---

## Validated Data

Prefer using validated data instead of directly using all request input.

```php
$validated = $request->validate([
    'name' => 'required|string',
    'email' => 'required|email',
]);

User::create($validated);
```

Instead of:

```php
User::create($request->all());
```

This helps ensure that only expected and validated fields are passed to the application.

---

## Key Points

* Validation checks incoming data against defined rules.
* `$request->validate()` is the simplest validation method.
* Laravel provides many built-in validation rules.
* Validation errors are automatically available through `$errors` in Blade.
* `old()` retrieves previously submitted input.
* Form Requests keep complex validation outside controllers.
* `validated()` returns only validated data.
* `unique` checks that a value does not already exist.
* `exists` checks that a value exists in a database table.
* Conditional rules can make validation depend on other fields.
* API validation returns validation errors as JSON.
* Validation and sanitization are different concepts.

### Interview Definition

> **Laravel validation is the process of checking incoming request data against predefined rules before allowing the application to process that data.**
