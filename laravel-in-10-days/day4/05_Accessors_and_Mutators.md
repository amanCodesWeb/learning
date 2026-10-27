# Accessors & Mutators

## Overview

**Accessors and Mutators** allow us to customize how Eloquent model attributes are **read and written**.

* **Accessor** → Changes a value when retrieving it.
* **Mutator** → Changes a value before storing it.

Example:

Database
    ↓
Accessor
    ↓
Value returned to application

Application value
    ↓
Mutator
    ↓
Database

---

## 1. Accessor

An **accessor** modifies an attribute when you access it.

Example:

Database:
name = aman singh

We want:
```php
$user->name
```

to return:
Aman Singh

### Using `Attribute`

Modern Laravel uses the `Attribute` class:

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function name(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucwords($value),
    );
}
```

Now:

```php
$user->name;
```

returns:
Aman Singh

The database value itself is not changed.

---

## 2. Mutator

A **mutator** modifies a value before it is stored in the database.

Example:

We want email addresses to always be stored in lowercase.

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function email(): Attribute
{
    return Attribute::make(
        set: fn ($value) => strtolower($value),
    );
}
```

Now:

```php
$user->email = 'AMAN@EXAMPLE.COM';
$user->save();
```

The database stores:
aman@example.com

---

## 3. Accessor + Mutator Together

An attribute can have both `get` and `set` behavior.

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function name(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucwords($value),
        set: fn ($value) => strtolower($value),
    );
}
```

When saving:

```php
$user->name = 'AMAN SINGH';
$user->save();
```

Database:
aman singh

When reading:
```php
echo $user->name;
```

Result:
Aman Singh

---

## 4. Accessor Example: Full Name

Suppose the database contains:
first_name
last_name

We want:
```php
$user->full_name;
```

to return:
Aman Singh

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function fullName(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) =>
            $attributes['first_name'] . ' ' . $attributes['last_name'],
    );
}
```

Now:

```php
echo $user->full_name;
```

returns:
Aman Singh

`full_name` does not need to exist as a database column.

---

## 5. Accessors Are Computed Attributes

An accessor can create a value from existing model data.

Example:

```php
protected function isAdult(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) =>
            $attributes['age'] >= 18,
    );
}
```

Usage:

```php
$user->is_adult;
```

Result:
true

This value can be calculated dynamically.

---

## 6. Mutator Example: Normalize Phone Number

```php
protected function phone(): Attribute
{
    return Attribute::make(
        set: fn ($value) => preg_replace('/\D/', '', $value),
    );
}
```

If:

```php
$user->phone = '+91 98765-43210';
```

The stored value becomes:
919876543210

This is useful for normalizing data before saving it.

---

## 7. Mutator Example: Password Hashing

A mutator can transform a password before saving it.

```php
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;

protected function password(): Attribute
{
    return Attribute::make(
        set: fn ($value) => Hash::make($value),
    );
}
```

Now:

```php
$user->password = 'secret123';
$user->save();
```

The database receives a hashed password rather than the plain text value.

---

## 8. Important: Accessor vs Mutator

| Feature   | Accessor                  | Mutator                   |
| --------- | ------------------------- | ------------------------- |
| Purpose   | Modify value when reading | Modify value when writing |
| Direction | Database → Application    | Application → Database    |
| Example   | Format name               | Lowercase email           |
| Trigger   | Attribute is accessed     | Attribute is assigned     |

Simple diagram:

```text
          DATABASE
             ↓
         ACCESSOR
             ↓
        APPLICATION
             ↓
         MUTATOR
             ↓
          DATABASE
```

---

## 9. Attribute Naming

Laravel's modern `Attribute` syntax uses camelCase method names:

```php
protected function fullName(): Attribute
{
    // ...
}
```

Laravel allows the attribute to be accessed using snake_case:

```php
$user->full_name;
```

Similarly:

```php
protected function phoneNumber(): Attribute
{
    // ...
}
```

can be accessed as:

```php
$user->phone_number;
```

---

## 10. Accessor vs Helper Method

An accessor is useful when a value should behave like a model attribute:

```php
$user->full_name;
```

A normal method is better when performing an action:

```php
$user->sendWelcomeEmail();
```

### Example

Computed value:

```php
$user->full_name;
```

Action:

```php
$user->sendEmail();
```

Do not turn every piece of model logic into an accessor.

---

## 11. Accessors and Database Queries

Accessors do not automatically create database columns or SQL expressions.

For example:

```php
protected function fullName(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) =>
            $attributes['first_name'] . ' ' . $attributes['last_name'],
    );
}
```

This works after the model has been retrieved.

You cannot normally use it directly in SQL:

```php
User::where('full_name', 'Aman Singh')->get();
```

because `full_name` is not a database column.

Instead, query the actual columns:

```php
User::where('first_name', 'Aman')
    ->where('last_name', 'Singh')
    ->get();
```

---

## 12. Accessor and Serialization

Computed attributes may need to be included when converting a model to an array or JSON.

For example, if you create:

```php
protected function fullName(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) =>
            $attributes['first_name'] . ' ' . $attributes['last_name'],
    );
}
```

and want `full_name` included in serialized output, you can add it to `$appends`:

```php
protected $appends = [
    'full_name',
];
```

Now:

```php
return $user;
```

can include:

```json
{
    "first_name": "Aman",
    "last_name": "Singh",
    "full_name": "Aman Singh"
}
```

---

## 13. Avoid Heavy Work in Accessors

Accessors are executed when the attribute is accessed.

Avoid expensive operations such as:
* Database queries
* Large calculations
* API requests
* Complex processing

Bad example:

```php
protected function ordersCount(): Attribute
{
    return Attribute::make(
        get: fn () => $this->orders()->count(),
    );
}
```

If used inside a loop, this can create unnecessary database queries.

For database-related calculations, use appropriate Eloquent queries such as:

```php
User::withCount('orders')->get();
```

---

## 14. Real-World E-Commerce Example

Suppose a product stores:

price = 999
discount = 10

We want:

```php
$product->final_price;
```

```php
protected function finalPrice(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) =>
            $attributes['price']
            - ($attributes['price'] * $attributes['discount'] / 100),
    );
}
```

Now:

```php
$product->final_price;
```

returns:
899.10

The calculated value does not need to be stored in the database.

---

## 15. Older Laravel Syntax

Older Laravel versions commonly used separate methods such as:

```php
public function getNameAttribute($value)
{
    return ucwords($value);
}

public function setEmailAttribute($value)
{
    $this->attributes['email'] = strtolower($value);
}
```

Modern Laravel generally uses the `Attribute::make()` approach:

```php
protected function name(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucwords($value),
    );
}
```

For current Laravel projects, prefer the modern syntax.

---

## Interview Questions

### Q: What is an accessor?

An accessor modifies or calculates an attribute when it is retrieved from a model.

```php
protected function name(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucwords($value),
    );
}
```

### Q: What is a mutator?

A mutator modifies an attribute before it is stored.

```php
protected function email(): Attribute
{
    return Attribute::make(
        set: fn ($value) => strtolower($value),
    );
}
```

### Q: What is the difference between an accessor and a mutator?

Accessor
Database → Application

Mutator
Application → Database

### Q: Can an accessor create a virtual attribute?

Yes.

For example:

```php
$user->full_name;
```

can be generated from:
first_name + last_name

without having a `full_name` database column.

### Q: Should accessors perform database queries?

Generally, no. Accessors should remain lightweight because they can be called many times, especially when models are processed in loops.

### Q: How do you include a computed accessor in JSON?

Use `$appends`:

```php
protected $appends = [
    'full_name',
];
```

---

## Remember

Accessor
    ↓
Read / Get
    ↓
Database → Application

Mutator
    ↓
Write / Set
    ↓
Application → Database

### Easy Example

```php
$user->name;
```

**Accessor:**

"aman singh"
      ↓
"Aman Singh"

```php
$user->email = 'AMAN@EXAMPLE.COM';
```

**Mutator:**

"AMAN@EXAMPLE.COM"
      ↓
"aman@example.com"

**Key rule:**

> Use accessors to transform values when reading, and mutators to transform values before storing them.
