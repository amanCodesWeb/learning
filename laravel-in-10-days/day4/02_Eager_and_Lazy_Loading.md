# Eager & Lazy Loading

## Overview

When using Eloquent relationships, Laravel can load related data in different ways.

The two main approaches are:
* **Lazy Loading** — load relationships only when they are accessed.
* **Eager Loading** — load relationships in advance.

---

## 1. Lazy Loading

By default, Eloquent uses **lazy loading**.
The relationship is loaded only when you access it.

```php
$user = User::find(1);

// Relationship is loaded here
$orders = $user->orders;
```

Initially, Laravel loads only the user:

```sql
SELECT * FROM users WHERE id = 1;
```

When `$user->orders` is accessed, Laravel runs another query:

```sql
SELECT * FROM orders WHERE user_id = 1;
```

### Advantage
Only load relationships when you actually need them.

### Disadvantage
If used inside loops, it can cause the **N+1 query problem**.

---

## 2. Eager Loading

Eager loading loads the relationship together with the main query.

Use the `with()` method:

```php
$users = User::with('orders')->get();
```

Laravel will generally execute:

```sql
SELECT * FROM users;
SELECT * FROM orders WHERE user_id IN (...);
```

Now:

```php
foreach ($users as $user) {
    echo $user->orders;
}
```

does not require another query for each user.

---

## 3. Lazy vs Eager Loading

### Lazy Loading

```php
$users = User::all();

foreach ($users as $user) {
    echo $user->orders;
}
```

The relationship is loaded when `$user->orders` is accessed.

### Eager Loading

```php
$users = User::with('orders')->get();

foreach ($users as $user) {
    echo $user->orders;
}
```

Orders are loaded before the loop.

---

## 4. Eager Loading Multiple Relationships

You can load multiple relationships:

```php
$users = User::with([
    'orders',
    'profile',
    'roles'
])->get();
```

---

## 5. Nested Eager Loading

You can eager load relationships inside relationships.

Example:

User
 └── Orders
      └── Products

```php
$users = User::with('orders.products')->get();
```

You can also use an array:

```php
$users = User::with([
    'orders.products'
])->get();
```

---

## 6. Eager Loading With Conditions

You can apply conditions to the relationship:

```php
$users = User::with([
    'orders' => function ($query) {
        $query->where('status', 'paid');
    }
])->get();
```

Only paid orders are eager loaded.

Modern Laravel syntax can also be written as:

```php
$users = User::with([
    'orders' => fn ($query) =>
        $query->where('status', 'paid')
])->get();
```

---

## 7. Eager Loading Selected Columns

You can select only the required columns:

```php
$users = User::with('orders:id,user_id,total')->get();
```

When selecting relationship columns, make sure the required foreign key is included.

For example:

orders:
id
user_id
total

`user_id` is needed to match orders with users.

---

## 8. Loading Relationships After Query

Sometimes you don't know whether a relationship is needed until after retrieving the model.

You can use `load()`:

```php
$user = User::find(1);
$user->load('orders');
```

Multiple relationships:

```php
$user->load([
    'orders',
    'profile'
]);
```

---

## 9. Conditional Loading

You can conditionally load a relationship:

```php
if ($showOrders) {
    $user->load('orders');
}
```

This is useful when the relationship is only needed under certain conditions.

---

## 10. `with()` vs `load()`

### `with()`

Used **before retrieving** the models:

```php
$users = User::with('orders')->get();
```

### `load()`

Used **after retrieving** the model:

```php
$users = User::all();
$users->load('orders');
```

Simple difference:

with() → Eager load during query
load() → Load relationship after query

---

## 11. Preventing Lazy Loading

In development, you can prevent accidental lazy loading:

```php
Model::preventLazyLoading();
```

Usually this can be enabled in `AppServiceProvider`:

```php
use Illuminate\Database\Eloquent\Model;

public function boot()
{
    Model::preventLazyLoading();
}
```

Now accidentally accessing an unloaded relationship can expose lazy-loading problems during development.

---

## 12. Default Eager Loading

If a model should always load a relationship, define `$with`:

```php
class User extends Model
{
    protected $with = ['profile'];
}
```

Now:

```php
$user = User::find(1);
```

automatically loads the profile.
Use this carefully because it adds the relationship query to every retrieval of that model.

---

## 13. Disabling Default Eager Loading

If a model has default eager loading but you don't need it for a particular query:

```php
$users = User::without('profile')->get();
```

This prevents the default relationship from being eager loaded for that query.

---

## 14. Why Eager Loading Matters

Consider:

```php
$users = User::all();

foreach ($users as $user) {
    echo $user->orders->count();
}
```

If there are 100 users:

1 query → get users
100 queries → get orders for each user

Total = 101 queries

This is called the **N+1 problem**.

Using:

```php
$users = User::with('orders')->get();
```

can reduce this to approximately:

1 query → get users
1 query → get all related orders

Total = 2 queries

---

## 15. Quick Comparison

| Feature       | Lazy Loading                   | Eager Loading                   |
| ------------- | ------------------------------ | ------------------------------- |
| Method        | Access relationship normally   | `with()`                        |
| When loaded   | When accessed                  | During initial query            |
| Extra queries | Can be many                    | Usually fewer                   |
| Good for      | Relationships you may not need | Relationships you know you need |
| Main risk     | N+1 queries                    | Loading unnecessary data        |

---

## Interview Questions

### Q: What is lazy loading?

Lazy loading means a relationship is queried only when the relationship is accessed.

```php
$user->orders;
```

### Q: What is eager loading?

Eager loading loads relationships in advance using methods such as:

```php
User::with('orders')->get();
```

### Q: What is the difference between `with()` and `load()`?

with() → Load relationships while building the query.
load() → Load relationships after the model has already been retrieved.

### Q: Why is eager loading useful?

It reduces unnecessary database queries and helps prevent the N+1 query problem.

### Q: Can eager loading load multiple relationships?

Yes:

```php
User::with([
    'orders',
    'profile',
    'roles'
])->get();
```

### Q: What is `$with` in an Eloquent model?

`$with` defines relationships that should be eager loaded automatically whenever the model is retrieved.

```php
protected $with = ['profile'];
```

---

## Remember

Lazy Loading
    ↓
Load relationship when accessed

Eager Loading
    ↓
Load relationship in advance

with()
    ↓
Eager load during query

load()
    ↓
Load after model/query result

N+1
    ↓
Many unnecessary relationship queries
