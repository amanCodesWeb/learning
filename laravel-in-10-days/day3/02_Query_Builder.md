# Query Builder

## Overview

Laravel's **Query Builder** provides a fluent way to interact with the database without writing raw SQL for most common operations.

It is accessed through the `DB` facade:

```php
use Illuminate\Support\Facades\DB;
```

Basic flow:

PHP Code
   ↓
Query Builder
   ↓
SQL
   ↓
Database

Example:
```php
$users = DB::table('users')->get();
```

---

## Selecting Data

### Get All Records

```php
$users = DB::table('users')->get();
```

Equivalent SQL:

```sql
SELECT * FROM users;
```

### Get Specific Columns

```php
$users = DB::table('users')
    ->select('id', 'name', 'email')
    ->get();
```

### Get a Single Record

```php
$user = DB::table('users')->find(1);
```

This searches using the primary key.

### Get First Record

```php
$user = DB::table('users')->first();
```

---

## `where()`

Filter records:

```php
$users = DB::table('users')
    ->where('status', 'active')
    ->get();
```

Multiple conditions:

```php
$users = DB::table('users')
    ->where('status', 'active')
    ->where('age', '>=', 18)
    ->get();
```

Basic structure:
```php
->where('column', 'operator', 'value')

->where('price', '>', 100)
```

When the operator is omitted:

```php
->where('status', 'active')
```

is equivalent to:

```php
->where('status', '=', 'active')
```

---

## `orWhere()`

```php
$users = DB::table('users')
    ->where('role', 'admin')
    ->orWhere('role', 'manager')
    ->get();
```

Equivalent idea:

```sql
WHERE role = 'admin' OR role = 'manager'
```

---

## `whereIn()`

Find records matching multiple values:

```php
$users = DB::table('users')
    ->whereIn('id', [1, 2, 3])
    ->get();
```

Useful for filtering by a list of IDs.

---

## `whereNotIn()`

```php
$users = DB::table('users')
    ->whereNotIn('id', [1, 2, 3])
    ->get();
```

---

## `whereNull()` and `whereNotNull()`

Find NULL values:

```php
$users = DB::table('users')
    ->whereNull('deleted_at')
    ->get();
```

Find non-NULL values:

```php
$users = DB::table('users')
    ->whereNotNull('email_verified_at')
    ->get();
```

---

## Ordering

### Ascending

```php
$users = DB::table('users')
    ->orderBy('name', 'asc')
    ->get();
```

### Descending

```php
$users = DB::table('users')
    ->orderBy('created_at', 'desc')
    ->get();
```

Shortcut:

```php
$users = DB::table('users')
    ->latest()
    ->get();
```

`latest()` normally orders by `created_at` descending.

---

## Limit and Offset

### Limit

```php
$users = DB::table('users')
    ->limit(10)
    ->get();
```

### Offset

```php
$users = DB::table('users')
    ->offset(10)
    ->limit(10)
    ->get();
```

---

## Pagination

For web applications, use `paginate()` when displaying large datasets:

```php
$users = DB::table('users')
    ->paginate(10);
```

This returns 10 records per page.

---

## Insert Data

Insert one record:

```php
DB::table('users')->insert([
    'name' => 'Aman',
    'email' => 'aman@example.com',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

Insert multiple records:

```php
DB::table('users')->insert([
    [
        'name' => 'Aman',
        'email' => 'aman@example.com',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'John',
        'email' => 'john@example.com',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
```

---

## Insert and Get ID

When inserting a record with an auto-incrementing primary key:

```php
$id = DB::table('users')->insertGetId([
    'name' => 'Aman',
    'email' => 'aman@example.com',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

`$id` contains the newly created user's ID.

---

## Update Data

Update records:

```php
DB::table('users')
    ->where('id', 1)
    ->update([
        'name' => 'Aman Singh',
    ]);
```

Multiple fields:

```php
DB::table('users')
    ->where('id', 1)
    ->update([
        'name' => 'Aman Singh',
        'status' => 'active',
    ]);
```

Update multiple records:

```php
DB::table('users')
    ->where('status', 'inactive')
    ->update([
        'status' => 'active',
    ]);
```

---

## Increment and Decrement

Increase a value:

```php
DB::table('products')
    ->where('id', 1)
    ->increment('stock');
```

Increase by a specific amount:

```php
DB::table('products')
    ->where('id', 1)
    ->increment('stock', 5);
```

Decrease:

```php
DB::table('products')
    ->where('id', 1)
    ->decrement('stock', 1);
```

Useful for inventory, counters, views, etc.

---

## Delete Data

Delete one record:

```php
DB::table('users')
    ->where('id', 1)
    ->delete();
```

Delete multiple records:

```php
DB::table('users')
    ->where('status', 'inactive')
    ->delete();
```

Delete all records:

```php
DB::table('users')->delete();
```
Be careful with queries that do not contain a `where()` condition.

---

## Joins

Query Builder can join multiple tables.

Example:

```php
$orders = DB::table('orders')
    ->join('users', 'orders.user_id', '=', 'users.id')
    ->select(
        'orders.id',
        'orders.total',
        'users.name'
    )
    ->get();
```

Conceptually:

```text
orders
   │
   │ user_id
   ↓
users.id
```

### Left Join

```php
$users = DB::table('users')
    ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
    ->select('users.name', 'orders.total')
    ->get();
```

---

## Aggregates

Query Builder provides aggregate functions.

### Count

```php
$count = DB::table('users')->count();
```

### Sum

```php
$total = DB::table('orders')->sum('total');
```

### Average

```php
$average = DB::table('products')->avg('price');
```

### Minimum

```php
$min = DB::table('products')->min('price');
```

### Maximum

```php
$max = DB::table('products')->max('price');
```

---

## `groupBy()`

Group records:

```php
$orders = DB::table('orders')
    ->select('status', DB::raw('COUNT(*) as total'))
    ->groupBy('status')
    ->get();
```

Example result:

```text
pending     10
completed   25
cancelled    3
```

---

## Raw Expressions

Laravel allows raw SQL expressions when Query Builder does not provide a convenient method.

```php
$orders = DB::table('orders')
    ->select(
        'status',
        DB::raw('COUNT(*) as total')
    )
    ->groupBy('status')
    ->get();
```

Avoid unnecessary raw SQL and never directly concatenate untrusted user input into raw SQL.

---

## Transactions

A transaction allows multiple database operations to succeed or fail together.

```php
DB::transaction(function () {

    $userId = DB::table('users')->insertGetId([
        'name' => 'Aman',
        'email' => 'aman@example.com',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('orders')->insert([
        'user_id' => $userId,
        'total' => 500,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});
```

If an exception occurs inside the transaction, the database changes are rolled back.

Operation 1
    ↓
Operation 2
    ↓
Operation 3
    ↓
All successful → COMMIT

Any failure
    ↓
ROLLBACK

Transactions are useful for operations such as:
Create Order
Create Order Items
Update Stock
Create Payment Record

---

## Query Builder vs Raw SQL

Raw SQL:

```php
$users = DB::select(
    'SELECT * FROM users WHERE status = ?',
    ['active']
);
```

Query Builder:

```php
$users = DB::table('users')
    ->where('status', 'active')
    ->get();
```

Query Builder is generally easier to read and maintain for common queries.

---

## Query Builder vs Eloquent

### Query Builder

```php
$users = DB::table('users')
    ->where('status', 'active')
    ->get();
```

### Eloquent

```php
$users = User::where('status', 'active')->get();
```

Main difference:

Query Builder
    ↓
Database tables
    ↓
Query results

Eloquent
    ↓
Models
    ↓
Query results as model objects

Query Builder is useful when you mainly need database-level queries, joins, aggregates, or complex queries without needing model behavior.

Eloquent is useful when working with application entities and relationships.

---

## Practical Example

Suppose we have:

products
├── id
├── name
├── price
├── stock
└── status

### Get Active Products

```php
$products = DB::table('products')
    ->where('status', 'active')
    ->get();
```

### Find One Product

```php
$product = DB::table('products')
    ->where('id', 1)
    ->first();
```

### Create Product

```php
DB::table('products')->insert([
    'name' => 'Laptop',
    'price' => 50000,
    'stock' => 10,
    'status' => 'active',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

### Update Product

```php
DB::table('products')
    ->where('id', 1)
    ->update([
        'price' => 55000,
    ]);
```

### Delete Product

```php
DB::table('products')
    ->where('id', 1)
    ->delete();
```

This gives the basic Query Builder CRUD flow:

Create → insert()
Read   → get() / first() / find()
Update → update()
Delete → delete()

---

## Common Methods

DB::table()

select()
get()
first()
find()

where()
orWhere()
whereIn()
whereNull()
whereNotNull()

orderBy()
latest()
oldest()

limit()
offset()
paginate()

insert()
insertGetId()

update()
increment()
decrement()

delete()

join()
leftJoin()

count()
sum()
avg()
min()
max()

groupBy()

DB::transaction()

---

## Key Points

* Query Builder provides a fluent interface for database queries.
* Use `DB::table()` to start a query.
* `get()` retrieves multiple records.
* `first()` retrieves the first matching record.
* `find()` retrieves a record by primary key.
* `where()` filters records.
* `insert()` creates records.
* `update()` modifies records.
* `delete()` removes records.
* `join()` combines data from multiple tables.
* Aggregate methods calculate values such as count, sum, and average.
* `paginate()` is useful for large result sets.
* Transactions keep related database operations consistent.
* Query Builder works directly with database tables, while Eloquent works through models.

### Interview Definition

> **Laravel Query Builder is a fluent database query interface that allows developers to build and execute SQL queries using PHP methods instead of writing raw SQL for every operation.**

### Practical CRUD Flow

DB::table('products')
        ↓
   ┌────┴────┐
   ↓         ↓
 Read      Create
get()      insert()
   ↓         ↓
 Update    Delete
update()   delete()
