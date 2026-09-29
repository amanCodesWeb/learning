# Query Scopes

## Overview

**Query Scopes** allow us to define reusable query conditions inside an Eloquent model.

Instead of repeatedly writing:

```php
User::where('status', 'active')->get();
```

we can create a reusable scope:

```php
User::active()->get();
```

Scopes help keep queries clean, readable, and reusable.

---

## 1. Local Scopes

A **local scope** is a reusable query method defined inside a model.

### Example

```php
class User extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
```

Now use it:

```php
$users = User::active()->get();
```

Laravel automatically recognizes methods starting with:

scope

So:
scopeActive()

is called as:
active()

---

## 2. Scope With Conditions

A scope can accept parameters.

```php
class User extends Model
{
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
```

Usage:

```php
$users = User::status('active')->get();
```

Another example:

```php
$users = User::status('inactive')->get();
```

---

## 3. Scope With Multiple Parameters

Scopes can accept multiple arguments.

```php
class Product extends Model
{
    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
}
```

Usage:

```php
$products = Product::priceBetween(100, 500)->get();
```

---

## 4. Chaining Scopes

Multiple scopes can be chained together.

```php
class Product extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
```

Usage:

```php
$products = Product::active()
    ->featured()
    ->get();
```

Conceptually:

```sql
SELECT *
FROM products
WHERE status = 'active'
AND featured = 1;
```

---

## 5. Scope With Other Eloquent Methods

Scopes can be combined with normal Eloquent queries.

```php
$users = User::active()
    ->where('age', '>=', 18)
    ->orderBy('name')
    ->get();
```

The scope is simply part of the query builder chain.

---

## 6. Scope With Relationships

Scopes can also be used on relationships.

Suppose `User` has many orders:

```php
public function orders()
{
    return $this->hasMany(Order::class);
}
```

And `Order` has an `active` scope:

```php
class Order extends Model
{
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
```

Now:

```php
$user->orders()
    ->paid()
    ->get();
```

This gets only paid orders belonging to that user.

---

## 7. Local Scope Naming

Use descriptive names.

Good:

```php
scopeActive()
scopePublished()
scopeFeatured()
scopePaid()
scopeRecent()
```

Avoid vague names:

```php
scopeData()
scopeFilter()
scopeQuery()
```

A scope should describe what it filters or changes.

---

## 8. Scopes for Common Business Queries

Scopes are useful when a condition is used repeatedly.

Without scope:

```php
Product::where('status', 'active')
    ->where('stock', '>', 0)
    ->get();
```

With scopes:

```php
Product::active()
    ->inStock()
    ->get();
```

Model:

```php
class Product extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
```

This makes business queries easier to understand.

---

## 9. Dynamic Scopes

A scope that accepts parameters is often called a **dynamic scope**.

Example:

```php
public function scopeCategory($query, $categoryId)
{
    return $query->where('category_id', $categoryId);
}
```

Usage:

```php
$products = Product::category(5)->get();
```

Another example:

```php
public function scopeMinPrice($query, $price)
{
    return $query->where('price', '>=', $price);
}
```

Usage:

```php
$products = Product::minPrice(500)->get();
```

---

## 10. Scope vs Normal Method

A normal method:

```php
public function getActiveUsers()
{
    return User::where('status', 'active')->get();
}
```

This returns a collection immediately.

A scope:

```php
public function scopeActive($query)
{
    return $query->where('status', 'active');
}
```

adds conditions to the query and allows further chaining:

```php
User::active()
    ->where('age', '>', 18)
    ->orderBy('name')
    ->get();
```

### Remember

Normal method
    ↓
Usually performs/returns an operation

Scope
    ↓
Adds reusable conditions to an Eloquent query

---

## 11. Global Scopes

A **global scope** automatically applies a condition to every query for a model.

Example use cases:

* Only active records
* Multi-tenant data
* Soft deletes
* Company-specific records

Example:

```php
class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', 'active');
    }
}
```

Register it in the model:

```php
protected static function booted()
{
    static::addGlobalScope(new ActiveScope);
}
```

Now:

```php
User::all();
```

automatically includes the global condition.

Conceptually:

```sql
SELECT *
FROM users
WHERE status = 'active';
```

---

## 12. Removing a Global Scope

Sometimes you need to retrieve records without a global scope.

Use:

```php
User::withoutGlobalScope(ActiveScope::class)->get();
```

For all global scopes:

```php
User::withoutGlobalScopes()->get();
```

Use this carefully because bypassing a global scope may expose records that are normally filtered.

---

## 13. Local vs Global Scopes

| Local Scope               | Global Scope                            |
| ------------------------- | --------------------------------------- |
| Applied manually          | Applied automatically                   |
| `User::active()`          | Automatically added                     |
| Explicit in query         | Hidden from query code                  |
| Good for reusable filters | Good for rules that should always apply |

---

## 14. Real-World Example

Imagine an e-commerce application with products.

```php
class Product extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
}
```

Now the controller can be:

```php
$products = Product::active()
    ->featured()
    ->inStock()
    ->priceBetween(500, 5000)
    ->get();
```

This is much easier to read than repeating all the query conditions.

---

## Interview Questions

### Q: What is a query scope?

A query scope is a reusable way to define query conditions inside an Eloquent model.

### Q: What is a local scope?

A scope that is explicitly called when needed.

```php
User::active()->get();
```

### Q: What is a dynamic scope?

A scope that accepts parameters.

```php
User::status('active')->get();
```

### Q: What is a global scope?

A scope that is automatically applied to every query for a model.

### Q: Why use query scopes?

They provide:

* Reusable query logic
* Cleaner controllers
* Better readability
* Easier maintenance
* Chainable queries

### Q: Can scopes be chained?

Yes.

```php
Product::active()
    ->featured()
    ->inStock()
    ->get();
```

---

## Remember

```text
Query Scope
    ↓
Reusable query condition

Local Scope
    ↓
User::active()

Dynamic Scope
    ↓
User::status('active')

Global Scope
    ↓
Automatically applied to model queries
```

**Key rule:**

> If the same Eloquent filtering logic is used repeatedly, consider putting it into a query scope.