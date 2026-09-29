# Eloquent Relationships

## Overview

**Eloquent Relationships** define how Laravel models are connected to each other.

Examples:
* A User has many Orders
* An Order belongs to a User
* A User has many Roles
* A Post has many Comments

Laravel provides relationship methods so we can work with related data without manually writing SQL joins.

---

## 1. One-to-One

One record is related to one other record.

**Example:** A User has one Profile.

### User.php

```php
public function profile()
{
    return $this->hasOne(Profile::class);
}
```

### Profile.php

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

### Usage

$user->profile;
$profile->user;

Database:

users
id
1

profiles
id | user_id
1  | 1

---

## 2. One-to-Many

One record can have many related records.
**Example:** A User can have many Orders.

### User.php

```php
public function orders()
{
    return $this->hasMany(Order::class);
}
```

### Order.php

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

### Usage

$user->orders;
$order->user;

Database:

users
id
1

orders
id | user_id
1  | 1
2  | 1
3  | 1

---

## 3. Many-to-Many

Many records can be related to many other records.
**Example:** A User can have many Roles, and a Role can belong to many Users.

### User.php

```php
public function roles()
{
    return $this->belongsToMany(Role::class);
}
```

### Role.php

```php
public function users()
{
    return $this->belongsToMany(User::class);
}
```

Laravel uses a **pivot table**:

role_user

user_id | role_id
1       | 1
1       | 2
2       | 1

### Usage

$user->roles;
$role->users;

---

## 4. Has-One-Through

Used when a model is related to another model through an intermediate model.

Example:
Country → User → Profile

```php
public function profile()
{
    return $this->hasOneThrough(
        Profile::class,
        User::class
    );
}
```

---

## 5. Has-Many-Through

Similar to `hasOneThrough()`, but returns multiple related records.

Example:
Country → Users → Orders

```php
public function orders()
{
    return $this->hasManyThrough(
        Order::class,
        User::class
    );
}
```

---

## 6. Polymorphic Relationships

A polymorphic relationship allows one model to belong to multiple different model types.

**Example:** A Comment can belong to a Post or a Video.

Database:

comments

id | commentable_id | commentable_type
1  | 10             | Post
2  | 5              | Video

### Comment.php

```php
public function commentable()
{
    return $this->morphTo();
}
```

### Post.php

```php
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

### Video.php

```php
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

### Usage

```php
$comment->commentable;
$post->comments;
$video->comments;
```

---

## 7. Relationship Method vs Property

The relationship is defined as a **method**:

```php
public function orders()
{
    return $this->hasMany(Order::class);
}
```

But related records are accessed as a **property**:
$user->orders;

Calling the method directly returns the relationship/query object:
$user->orders();

This allows us to add query conditions:

```php
$user->orders()
     ->where('status', 'paid')
     ->get();
```

### Remember

```php
$user->orders;   // Related models
$user->orders(); // Relationship/query object
```

---

## 8. Creating Related Records

Without relationship:

```php
Order::create([
    'user_id' => $user->id,
    'total' => 100
]);
```

Using the relationship:

```php
$user->orders()->create([
    'total' => 100
]);
```

Laravel automatically sets the foreign key:

user_id = $user->id

---

## 9. Foreign Key Convention

Laravel follows naming conventions.

For:

```php
return $this->belongsTo(User::class);
```

Laravel normally expects:
user_id

If the foreign key has a different name:

```php
return $this->belongsTo(User::class, 'customer_id');
```

---

## 10. Common Relationship Methods

| Relationship         | Method                     |
| -------------------- | -------------------------- |
| One-to-One           | `hasOne()`                 |
| One-to-Many          | `hasMany()`                |
| Inverse relationship | `belongsTo()`              |
| Many-to-Many         | `belongsToMany()`          |
| One-through          | `hasOneThrough()`          |
| Many-through         | `hasManyThrough()`         |
| Polymorphic          | `morphTo()`, `morphMany()` |

---

## 11. Quick Relationship Diagram

User
 ├── hasOne → Profile
 ├── hasMany → Orders
 └── belongsToMany → Roles

Order
 └── belongsTo → User

Post
 └── morphMany → Comments

Video
 └── morphMany → Comments

Comment
 └── morphTo → Post / Video

---

## Interview Questions

### Q: What is an Eloquent relationship?

An Eloquent relationship defines how two or more Laravel models are connected and provides methods for retrieving related records.

### Q: Difference between `hasMany()` and `belongsTo()`?

User → hasMany → Orders
Order → belongsTo → User

The model that contains the foreign key normally uses `belongsTo()`.

### Q: What is a pivot table?

A pivot table is an intermediate table used to store relationships between two models in a many-to-many relationship.

Example:

user_role
user_id | role_id

### Q: Why use Eloquent relationships?

They make related-data access easier and more readable while integrating with other Eloquent features such as eager loading, relationship queries, and constraints.
