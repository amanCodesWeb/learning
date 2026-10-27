# N+1 Query Problem

## Overview

The **N+1 problem** happens when Laravel executes:
* 1 query to retrieve the main records
* N additional queries to retrieve a relationship for each record

This can cause a large number of unnecessary database queries.

---

## 1. Example of N+1

Suppose we have:

User
 └── hasMany → Orders

Now we write:

```php
$users = User::all();

foreach ($users as $user) {
    echo $user->orders->count();
}
```

Laravel first executes:

```sql
SELECT * FROM users;
```

If there are 100 users, accessing `$user->orders` can then execute:

```sql
SELECT * FROM orders WHERE user_id = 1;
SELECT * FROM orders WHERE user_id = 2;
SELECT * FROM orders WHERE user_id = 3;
...
```

So the application can execute:

1 query + 100 relationship queries
= 101 queries

This is the **N+1 problem**.

---

## 2. Why Is It Called N+1?

`N` represents the number of records retrieved.

1 → Query to retrieve users
N → Query for each user's orders

Total → N + 1 queries

For example:

10 users  → 11 queries
100 users → 101 queries
1000 users → 1001 queries

The larger the dataset, the worse the problem can become.

---

## 3. Solving N+1 With Eager Loading

Use `with()`:

```php
$users = User::with('orders')->get();

foreach ($users as $user) {
    echo $user->orders->count();
}
```

Laravel can load the users and their orders using approximately:

1 query → users
1 query → orders

Total ≈ 2 queries

Instead of:
1 + N queries

---

## 4. SQL Comparison

### Without Eager Loading

```php
$users = User::all();

foreach ($users as $user) {
    $user->orders;
}
```

Conceptually:

```sql
SELECT * FROM users;

SELECT * FROM orders WHERE user_id = 1;
SELECT * FROM orders WHERE user_id = 2;
SELECT * FROM orders WHERE user_id = 3;
...
```

---

### With Eager Loading

```php
$users = User::with('orders')->get();
```

Conceptually:

```sql
SELECT * FROM users;

SELECT * FROM orders
WHERE user_id IN (1, 2, 3, ...);
```

The exact SQL can vary, but the important point is that Laravel avoids running one relationship query for every user.

---

## 5. N+1 With Nested Relationships

The problem can also happen with nested relationships.

Example:

User
 └── Orders
      └── Products

Bad:

```php
$users = User::all();

foreach ($users as $user) {
    foreach ($user->orders as $order) {
        echo $order->products;
    }
}
```

Both relationships may generate additional queries.

Use nested eager loading:

```php
$users = User::with('orders.products')->get();
```

Now Laravel loads the required relationships in advance.

---

## 6. N+1 When Using `belongsTo`

The problem is not limited to `hasMany()`.

Example:

```php
$orders = Order::all();

foreach ($orders as $order) {
    echo $order->user->name;
}
```

If there are 100 orders, this can result in:

1 query → orders
100 queries → users

Total = 101 queries

Fix:

```php
$orders = Order::with('user')->get();
```

---

## 7. N+1 With Blade

N+1 problems can easily appear in Blade templates.

Example:

```blade
@foreach ($orders as $order)
    {{ $order->user->name }}
@endforeach
```

If `user` wasn't eager loaded, every iteration can trigger another query.

Controller:

```php
$orders = Order::with('user')->get();
return view('orders.index', compact('orders'));
```

Blade:

```blade
@foreach ($orders as $order)
    {{ $order->user->name }}
@endforeach
```

The view can now use the already-loaded relationship.

---

## 8. Conditional Eager Loading

Sometimes you only need a relationship under certain conditions.

```php
$users = User::query();

if ($includeOrders) {
    $users->with('orders');
}

$users = $users->get();
```

This avoids loading relationships that aren't required.

---

## 9. Eager Loading With Conditions

You can also limit which related records are loaded:

```php
$users = User::with([
    'orders' => function ($query) {
        $query->where('status', 'paid');
    }
])->get();
```

Now only paid orders are eager loaded.

---

## 10. Detecting N+1 Problems

### Laravel Debugging Tools

Development tools such as **Laravel Debugbar** can show:
* Number of queries
* SQL queries
* Query execution time
* Duplicate queries

This makes N+1 problems easier to identify during development.
You can also use Laravel's lazy-loading prevention:

```php
Model::preventLazyLoading();
```

This helps detect accidental lazy loading during development.

---

## 11. N+1 Is a Performance Problem

N+1 doesn't always mean the application will immediately become slow.

For a small dataset:
5 users

the difference may be small.

For a large dataset:
10,000 users

running thousands of additional queries can significantly increase:
* Database load
* Response time
* Server resource usage
* Network/database overhead

Therefore, relationship loading should be considered when designing database-heavy pages and APIs.

---

## 12. Important: Eager Loading Is Not Always Better

Don't automatically eager load every relationship.

For example:

```php
User::with([
    'orders',
    'roles',
    'profile',
    'addresses',
    'payments'
])->get();
```

If the page only needs the user's profile, loading everything can waste resources.

Load relationships that the current operation actually needs:

```php
User::with('profile')->get();
```

The goal is:

Avoid unnecessary queries
        +
Avoid unnecessary data

---

## 13. Common N+1 Pattern

### Problem

```php
$posts = Post::all();

foreach ($posts as $post) {
    echo $post->author->name;
}
```

### Solution

```php
$posts = Post::with('author')->get();

foreach ($posts as $post) {
    echo $post->author->name;
}
```

---

## 14. Quick Comparison

| Approach                                | Queries | Problem   |
| --------------------------------------- | ------: | --------- |
| `User::all()` + `$user->orders` in loop |   1 + N | N+1       |
| `User::with('orders')->get()`           |      ~2 | Efficient |
| `Order::all()` + `$order->user` in loop |   1 + N | N+1       |
| `Order::with('user')->get()`            |      ~2 | Efficient |

---

## Interview Questions

### Q: What is the N+1 problem?

It occurs when one query retrieves the main records and an additional query is executed for each record to retrieve its relationship.

### Q: How do you solve N+1 in Laravel?

Use **eager loading**:

```php
$users = User::with('orders')->get();
```

### Q: Can N+1 happen with `belongsTo()`?

Yes.

```php
$orders = Order::all();

foreach ($orders as $order) {
    echo $order->user->name;
}
```

Can be solved with:

```php
$orders = Order::with('user')->get();
```

### Q: Can N+1 happen in Blade?

Yes. Accessing an unloaded relationship inside a loop can cause N+1 queries.

### Q: Should we eager load every relationship?

No. Eager load relationships that are actually required. Loading unnecessary relationships can increase memory usage and database work.

---

## Remember

```text
N+1 Problem

1 query
   ↓
Get N records
   ↓
N additional relationship queries
   ↓
Total = N + 1 queries

Solution
   ↓
Eager Loading
   ↓
with()
   ↓
User::with('orders')->get()
```

**Key rule:**

> If you access an Eloquent relationship inside a loop, think about whether it should be eager loaded.