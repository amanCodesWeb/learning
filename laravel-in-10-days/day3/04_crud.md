# Eloquent CRUD

## Overview

**CRUD** stands for:

Create → Insert data
Read   → Retrieve data
Update → Modify data
Delete → Remove data

Eloquent provides simple methods for performing all four operations through Models.

Assume we have a `User` model:

```php
use App\Models\User;
```

---

## 1. Create

### Using `create()`

```php
$user = User::create([
    'name' => 'Aman',
    'email' => 'aman@example.com',
    'password' => bcrypt('password'),
]);
```

For `create()` to work, the fields must be allowed in the model:

```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

### Using Model Instance

```php
$user = new User();

$user->name = 'Aman';
$user->email = 'aman@example.com';
$user->password = bcrypt('password');

$user->save();
```

`save()` inserts the new record into the database.

---

## 2. Read

### Get all records

```php
$users = User::all();
```

### Find by ID

```php
$user = User::find(1);
```

### Find or fail

```php
$user = User::findOrFail(1);
```

### Find using conditions

```php
$users = User::where('status', 'active')->get();
```

Multiple conditions:

```php
$users = User::where('status', 'active')
    ->where('is_admin', 1)
    ->get();
```

### Get a single record

```php
$user = User::where('email', 'aman@example.com')->first();
```

---

## 3. Update

### Update using Model

```php
$user = User::find(1);

$user->name = 'Aman Singh';
$user->save();
```

### Update using `update()`

```php
$user = User::find(1);

$user->update([
    'name' => 'Aman Singh',
    'status' => 'active',
]);
```

The fields used with `update()` must be allowed by `$fillable`.

### Bulk Update

Update multiple records directly:

```php
User::where('status', 'inactive')
    ->update([
        'status' => 'active',
    ]);
```

This updates all matching records.

---

## 4. Delete

### Delete a Model

```php
$user = User::find(1);

$user->delete();
```

### Delete using Query

```php
User::where('id', 1)->delete();
```

### Delete Multiple Records

```php
User::where('status', 'inactive')->delete();
```

---

## Complete CRUD Example

```php
// CREATE
$user = User::create([
    'name' => 'Aman',
    'email' => 'aman@example.com',
]);

// READ
$user = User::find(1);

// UPDATE
$user->update([
    'name' => 'Aman Singh',
]);

// DELETE
$user->delete();
```

---

## `create()` vs `save()`

### `create()`

```php
User::create([
    'name' => 'Aman',
]);
```

Creates and saves the record in one step.

Requires `$fillable` or `$guarded`.

### `save()`

```php
$user = new User();

$user->name = 'Aman';

$user->save();
```

You create the Model object, set its properties, and explicitly save it.

---

## `$fillable`

`$fillable` protects against **mass assignment**.

```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

Now this is allowed:

```php
User::create([
    'name' => 'Aman',
    'email' => 'aman@example.com',
]);
```

But fields not included in `$fillable` cannot normally be mass-assigned.

---

## `$guarded`

Instead of specifying allowed fields, you can specify protected fields:

```php
protected $guarded = [
    'id',
];
```

In general, `$fillable` is commonly preferred because it explicitly defines which fields can be mass-assigned.

---

## CRUD Cheat Sheet

| Operation           | Eloquent          |
| ------------------- | ----------------- |
| Create              | `User::create()`  |
| Read all            | `User::all()`     |
| Read one            | `User::find()`    |
| Read with condition | `User::where()`   |
| Update              | `$user->update()` |
| Delete              | `$user->delete()` |

## Key Points

* Eloquent CRUD works through Models.
* `create()` performs mass assignment and requires `$fillable`/`$guarded` configuration.
* `save()` saves a Model instance.
* `find()` searches by primary key.
* `where()` adds conditions.
* `update()` modifies existing records.
* `delete()` removes records.
* Bulk updates/deletes can be performed directly through queries.
* `$fillable` helps protect against unwanted mass assignment.
