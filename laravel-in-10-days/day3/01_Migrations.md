# Migrations

## Overview

A **migration** is Laravel's way of managing database structure using PHP code.

Instead of manually creating or modifying tables in phpMyAdmin, we define the database structure in migration files.

Migration
    ↓
Database Table

Migrations make database changes:
* Trackable
* Repeatable
* Version controlled
* Easy to share with other developers
* Easy to rollback

---

## Migration Location

Migration files are stored in:
database/migrations/

Example:
database/migrations/
    2026_09_26_000001_create_users_table.php
    2026_09_26_000002_create_products_table.php

The timestamp determines the order in which migrations run.

---

## Creating a Migration

Create a migration:

```bash
php artisan make:migration create_products_table
```

For a new table, Laravel generates a file containing `up()` and `down()` methods.

Example:

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

## `up()` and `down()`

### `up()`

Defines what should happen when the migration runs.

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
    });
}
```

### `down()`

Defines how to undo the migration.

```php
public function down(): void
{
    Schema::dropIfExists('products');
}
```

Simple idea:
up()   → Apply change
down() → Undo change

---

## Creating Tables

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

This creates:

products
├── id
├── name
├── description
├── price
├── stock
├── is_active
├── created_at
└── updated_at

---

## Common Column Types

```php
$table->id();
$table->string('name');
$table->text('description');
$table->integer('stock');
$table->unsignedInteger('quantity');
$table->decimal('price', 10, 2);
$table->boolean('is_active');
$table->date('published_at');
$table->dateTime('starts_at');
$table->timestamp('verified_at');
$table->json('settings');
```

Commonly used:

| Column   | Example                           |
| -------- | --------------------------------- |
| ID       | `$table->id()`                    |
| String   | `$table->string('name')`          |
| Text     | `$table->text('description')`     |
| Integer  | `$table->integer('stock')`        |
| Decimal  | `$table->decimal('price', 10, 2)` |
| Boolean  | `$table->boolean('active')`       |
| Date     | `$table->date('date')`            |
| DateTime | `$table->dateTime('starts_at')`   |
| JSON     | `$table->json('settings')`        |

---

## Nullable Columns

By default, a column is not nullable.

```php
$table->string('name');
```

To allow `NULL`:

```php
$table->string('phone')->nullable();
```

---

## Default Values

```php
$table->integer('stock')->default(0);
$table->boolean('is_active')->default(true);
$table->string('status')->default('pending');
```

---

## Timestamps

```php
$table->timestamps();
```

Creates:
created_at
updated_at

Laravel models normally use these automatically.

---

## Primary Key

The common Laravel primary key is:

```php
$table->id();
```

It creates an auto-incrementing unsigned BIGINT primary key named `id`.

---

## Foreign Keys

Foreign keys connect records between tables.

Example:

```php
$table->foreignId('user_id')->constrained();
```

This typically creates:
user_id

and references:
users.id

Example:

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->decimal('total', 10, 2);
    $table->timestamps();
});
```

Relationship:

```text
users
  │
  │ id
  ↓
orders
  │
  └── user_id
```

---

## Foreign Key with Nullable Value

If the relationship is optional:

```php
$table->foreignId('user_id')
    ->nullable()
    ->constrained();
```

The order can now exist without a user.

---

## Foreign Key Delete Behavior

You can define what happens when the parent record is deleted.

### Cascade

```php
$table->foreignId('user_id')
    ->constrained()
    ->cascadeOnDelete();
```

Deleting the user also deletes related records.

### Set NULL

```php
$table->foreignId('user_id')
    ->nullable()
    ->constrained()
    ->nullOnDelete();
```

Deleting the user sets `user_id` to `NULL`.

### Restrict

```php
$table->foreignId('user_id')
    ->constrained()
    ->restrictOnDelete();
```

The parent cannot be deleted while related records exist.

---

## Modifying Existing Tables

Create a migration:

```bash
php artisan make:migration add_status_to_products_table
```

Then:

```php
Schema::table('products', function (Blueprint $table) {
    $table->string('status')->default('active');
});
```

To remove a column:

```php
Schema::table('products', function (Blueprint $table) {
    $table->dropColumn('status');
});
```

---

## Renaming Columns

```php
Schema::table('products', function (Blueprint $table) {
    $table->renameColumn('name', 'product_name');
});
```

---

## Indexes

Indexes improve database lookup performance.

```php
$table->index('email');
```

Unique index:

```php
$table->unique('email');
```

Example:

```php
$table->string('email')->unique();
```

Now duplicate email addresses are not allowed at the database level.

---

## Running Migrations

Run all pending migrations:

```bash
php artisan migrate
```

Check migration status:

```bash
php artisan migrate:status
```

---

## Rolling Back

Rollback the latest migration batch:

```bash
php artisan migrate:rollback
```

Rollback one step:

```bash
php artisan migrate:rollback --step=1
```

---

## Reset and Refresh

### Reset

Rollback all migrations:

```bash
php artisan migrate:reset
```

### Refresh

Rollback all migrations and run them again:

```bash
php artisan migrate:refresh
```

### Fresh

Drop all tables and run migrations again:

```bash
php artisan migrate:fresh
```

With seeders:

```bash
php artisan migrate:fresh --seed
```

### Difference

migrate
    → Run pending migrations

rollback
    → Undo latest migration batch

reset
    → Undo all migrations

refresh
    → Rollback + migrate

fresh
    → Drop all tables + migrate

`migrate:fresh` is especially useful during local development when you want to rebuild the database from scratch.

---

## Migration Example: Products

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

Result:

products
├── id
├── name
├── description
├── price
├── stock
├── is_active
├── created_at
└── updated_at

---

## Migration Best Practices

* One migration should represent one database change.
* Don't edit old migrations that have already been run/shared.
* Create a new migration for later database changes.
* Use foreign keys for relationships.
* Use database constraints such as `unique()` where appropriate.
* Use `nullable()` only when a value is genuinely optional.
* Keep migrations in version control.
* Use `migrate:fresh` carefully because it deletes existing tables/data.

---

## Migration vs Database Seeder

These are different:

**Migration**

Defines database structure:
Tables
Columns
Indexes
Foreign Keys

**Seeder**

Adds initial/sample data:
Admin user
Categories
Demo products

Example:

Migration
   ↓
Create products table
   ↓
Seeder
   ↓
Insert products

---

## Key Points

* Migrations manage database structure using code.
* Migration files are stored in `database/migrations`.
* `up()` applies a database change.
* `down()` reverses a database change.
* `Schema::create()` creates a table.
* `Schema::table()` modifies an existing table.
* `$table->id()` creates the primary key.
* `$table->foreignId()` is commonly used for foreign keys.
* `$table->timestamps()` creates `created_at` and `updated_at`.
* `nullable()` allows `NULL`.
* `default()` defines a default value.
* `unique()` prevents duplicate values.
* `php artisan migrate` runs pending migrations.
* `php artisan migrate:rollback` reverses the latest batch.
* `php artisan migrate:fresh` drops all tables and rebuilds them.

### Interview Definition

> **A Laravel migration is a version-controlled PHP file used to create, modify, and manage the structure of a database.**

### Practical Flow

```text
Create Migration
      ↓
Define Table Structure
      ↓
php artisan migrate
      ↓
Database Table Created
      ↓
Create Model
      ↓
Use Eloquent / Query Builder
      ↓
CRUD
```