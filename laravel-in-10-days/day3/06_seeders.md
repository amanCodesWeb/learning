# Laravel Database Seeders

## Definition

A **Seeder** is a Laravel class used to insert predefined or test data into the database.

Seeders are mainly used for:

- Creating initial application data
- Adding test/development data
- Creating default users or roles
- Populating related tables
- Running factories

Seeder files are stored in:
    database/seeders/

---

## Why Use Seeders?

Instead of manually inserting data into the database:

    INSERT INTO users ...

Laravel allows you to define the data in a reusable PHP class:

    User::factory()->count(10)->create();

Then run:

    php artisan db:seed

This makes database setup easier and repeatable.

---

## Creating a Seeder

Create a seeder:

    php artisan make:seeder UserSeeder

Laravel creates:

    database/seeders/UserSeeder.php

Basic structure:

    class UserSeeder extends Seeder
    {
        public function run(): void
        {
            // Insert data here
        }
    }

---

## Basic Seeder

You can directly create records:

    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }

---

## Running a Seeder

Run all registered seeders:
    php artisan db:seed

Run a specific seeder:
    php artisan db:seed --class=UserSeeder

---

## DatabaseSeeder

Laravel provides a main seeder:

    database/seeders/DatabaseSeeder.php

It is commonly used to call other seeders.

Example:

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }

Then:

    php artisan db:seed

Flow:

    DatabaseSeeder
          ↓
    UserSeeder
    ProductSeeder
    OrderSeeder

---

## Seeder + Factory

Factories are commonly used inside seeders.

Example:

    public function run(): void
    {
        User::factory()->count(50)->create();
    }

Run:

    php artisan db:seed

This creates 50 users.

---

## Seeder + Factory + Relationships

Example:

    public function run(): void
    {
        User::factory()
            ->count(10)
            ->has(Post::factory()->count(3))
            ->create();
    }

This creates:

    10 Users
       ↓
    3 Posts per User

---

## Fixed / Default Data

Seeders are also useful for data that should always exist.

Example:

    Role::create([
        'name' => 'Admin',
    ]);

    Role::create([
        'name' => 'Customer',
    ]);

Examples:

- Roles
- Permissions
- Default settings
- Countries
- Categories
- Application configuration data

---

## Seeder vs Factory

### Factory

Defines **how fake data should look**.

    UserFactory
        ↓
    name
    email
    password

### Seeder

Defines **what data should be inserted**.

    UserSeeder
        ↓
    Create 50 users

Simple rule:

    Factory = Data structure
    Seeder  = Data insertion

---

## Seeder vs Migration

### Migration

Defines and changes the database structure.

    users table
        ↓
    id
    name
    email

### Seeder

Inserts data into that structure.

    users table
        ↓
    Admin
    Customer
    Test User

Simple rule:

    Migration = Database structure
    Seeder   = Database data

---

## Fresh Database with Seeders

You can rebuild the database and run seeders:
    php artisan migrate:fresh --seed

This:
1. Drops all tables.
2. Runs migrations again.
3. Runs `DatabaseSeeder`.

Useful for local development and testing.

---

## Calling Seeders in Order

Seeder order can matter when records depend on each other.

Example:

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }

Here roles are created before users if users depend on roles.

---

## Important Difference

    Factory
       ↓
    Generates model data

    Seeder
       ↓
    Inserts predefined/test data

    Migration
       ↓
    Creates or changes database structure

---

## Key Points

- Seeders insert data into the database.
- Seeder files are stored in `database/seeders`.
- `DatabaseSeeder` is the main entry point.
- Use `$this->call()` to run other seeders.
- Factories are commonly used inside seeders.
- `db:seed` runs the seeders.
- `migrate:fresh --seed` rebuilds the database and seeds it.
- Seeders are useful for default, development, and test data.

---

## Interview Questions

### What is a Seeder?

A Laravel class used to insert predefined or test data into the database.

### Where are seeders stored?

    database/seeders/

### How do you create a seeder?

    php artisan make:seeder UserSeeder

### How do you run a specific seeder?

    php artisan db:seed --class=UserSeeder

### What is DatabaseSeeder?

The main seeder used to call other application seeders.

### Factory vs Seeder?

Factory defines how data is generated; Seeder controls what data is inserted.

### Migration vs Seeder?

Migration defines database structure; Seeder inserts database data.