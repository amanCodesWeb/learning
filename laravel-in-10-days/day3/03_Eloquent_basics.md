# Eloquent Basics

## Overview

**Eloquent** is Laravel's built-in **ORM (Object-Relational Mapping)**.

It allows us to work with database tables using **PHP Models** instead of writing SQL queries directly.

```php
User::all();
```

instead of:

```sql
SELECT * FROM users;
```

## Model

A Model represents a database table.

```bash
php artisan make:model User
```

This creates:
app/Models/User.php

Example:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
}
```

By convention:

Model       → Table
User        → users
Product     → products
Order       → orders

Eloquent automatically maps the model to its corresponding table.

## Basic Queries

Get all records:

```php
$users = User::all();
```

Find by primary key:

```php
$user = User::find(1);
```

Find or fail:

```php
$user = User::findOrFail(1);
```

Get the first record:

```php
$user = User::first();
```

Filter records:

```php
$users = User::where('status', 'active')->get();
```

Get one matching record:

```php
$user = User::where('email', 'aman@example.com')->first();
```

## Query Methods

`get()` returns multiple records:

```php
$users = User::where('status', 'active')->get();
```

`first()` returns one record:

```php
$user = User::where('status', 'active')->first();
```

`find()` searches by primary key:

```php
$user = User::find(5);
```

`findOrFail()` throws a 404-style exception when the record doesn't exist:

```php
$user = User::findOrFail(5);
```

## Selecting Columns

```php
$users = User::select('id', 'name', 'email')->get();
```

## Ordering

```php
$users = User::orderBy('name', 'asc')->get();
```

Descending:

```php
$users = User::orderBy('created_at', 'desc')->get();
```

## Eloquent vs Query Builder

### Eloquent

```php
User::where('status', 'active')->get();
```

### Query Builder

```php
DB::table('users')
    ->where('status', 'active')
    ->get();
```

Eloquent works through **Models**, while Query Builder works more directly with **database tables and queries**.

## Important

Eloquent does **not** mean you never use Query Builder.

A Laravel application can use:

Eloquent
   +
Query Builder
   +
Raw SQL

Choose the approach that fits the operation.

## Key Points

* Eloquent is Laravel's ORM.
* A Model normally represents a database table.
* Models are stored in `app/Models`.
* Eloquent uses PHP objects to interact with database records.
* Eloquent supports querying, inserting, updating and deleting records.
* Eloquent also supports relationships such as `hasMany()` and `belongsTo()`.


I use Query Builder when I need to work directly with database queries rather than model-specific functionality. It's especially useful for complex joins, aggregations, bulk updates, or queries where I don't need Eloquent's model features. Eloquent is preferable when I'm working with application models and relationships.