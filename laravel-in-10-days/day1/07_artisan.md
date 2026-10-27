# 07. Artisan CLI

## Overview

**Artisan** is Laravel's command-line interface (CLI).

It provides commands for:

- Creating Laravel components
- Running database operations
- Managing queues
- Clearing caches
- Inspecting application information
- Running scheduled tasks
- Running tests
- Creating custom commands

The basic syntax is:
    php artisan <command>


## 1. Check Available Commands
    
    php artisan

Shows the available Artisan commands.

To get help for a specific command:
    php artisan help migrate


## 2. Start the Laravel Development Server

    php artisan serve

Starts Laravel's local development server.

Usually accessible at:

    http://127.0.0.1:8000


## 3. Route Commands

List registered routes:

    php artisan route:list

This is useful for checking:
- HTTP method
- URI
- Route name
- Controller/action
- Middleware

Clear the route cache:
    php artisan route:clear


## 4. Create Models

Create a model:
    php artisan make:model Product

This creates:
    app/Models/Product.php


Create a model with a migration:
    php artisan make:model Product -m

This creates:

    Model
    +
    Migration


Common combinations:
    php artisan make:model Product -m
    php artisan make:model Product -mf
    php artisan make:model Product -mfs

Where:
    -m → Migration
    -f → Factory
    -s → Seeder


## 5. Create Controllers

    php artisan make:controller ProductController

Creates:
    app/Http/Controllers/ProductController.php


Resource controller:
    php artisan make:controller ProductController --resource

A resource controller provides methods commonly used for

CRUD operations:
    index
    create
    store
    show
    edit
    update
    destroy


## 6. Create Migrations

    php artisan make:migration create_products_table

Creates a migration file inside:
    database/migrations/


Run migrations:
    php artisan migrate


Rollback the latest migration batch:
    php artisan migrate:rollback


Reset all migrations:
    php artisan migrate:reset


Rollback and run migrations again:
    php artisan migrate:refresh


Drop all tables and run migrations again:
    php artisan migrate:fresh


`migrate:fresh` should be used carefully because it deletes
the existing database tables.


## 7. Create Middleware

    php artisan make:middleware CheckAdmin

Creates:
    app/Http/Middleware/CheckAdmin.php


## 8. Create Form Requests

Form Requests are useful for keeping validation logic separate from controllers.

Create one:

    php artisan make:request StoreProductRequest

Creates:
    app/Http/Requests/StoreProductRequest.php


## 9. Create Jobs

Create a job:
    php artisan make:job SyncShopifyOrders

Creates a job class that can be dispatched to a queue.

Example:
    SyncShopifyOrders::dispatch($shop);


Run the queue worker:
    php artisan queue:work


## 10. Create Events and Listeners

Create an event:
    php artisan make:event OrderPlaced

Create a listener:
    php artisan make:listener SendOrderEmail


Typical flow:

    OrderPlaced
        ↓
    SendOrderEmail


## 11. Create Services

Laravel does not have a built-in `make:service` command in the same way it has commands for controllers or models.

A service can simply be created manually, for example:
    app/Services/OrderService.php


Example:

    class OrderService
    {
        public function create()
        {
            // Business logic
        }
    }


## 12. Cache Commands

Clear application cache:
    php artisan cache:clear


Clear configuration cache:
    php artisan config:clear


Create configuration cache:
    php artisan config:cache


Clear route cache:
    php artisan route:clear


Clear view cache:
    php artisan view:clear


Clear various framework caches:
    php artisan optimize:clear


`optimize:clear` is useful when old cached data is causing unexpected behavior during development.


## 13. Storage Link

Laravel commonly stores uploaded files in:
    storage/app/public

To make them accessible through the `public` directory:
    php artisan storage:link


This creates a symbolic link:

    public/storage
        ↓
    storage/app/public


## 14. Run Tests

Laravel tests can be executed using:
    php artisan test

You can also run specific tests:
    php artisan test tests/Feature/ProductTest.php


## 15. Tinker

Tinker provides an interactive shell for working with the Laravel application.

Start it:
    php artisan tinker


Example:

    $user = User::find(1);
    $products = Product::where('status', 'active')->get();


Tinker is very useful for quickly testing:
- Models
- Database queries
- Services
- Application logic
- Relationships


## 16. Maintenance Mode

Put the application into maintenance mode:
    php artisan down


Bring it back online:
    php artisan up

This is commonly useful during deployments or maintenance.


## 17. Scheduler

Laravel scheduled tasks can be executed using:
    php artisan schedule:run


In production, this command is commonly triggered periodically by the server's cron system.


## 18. Custom Artisan Commands

You can create your own Artisan command:
    php artisan make:command SyncOrders

This creates a custom command inside:
    app/Console/Commands/


Example usage:
    php artisan orders:sync


Custom commands are useful for tasks such as:
    Importing products
    Syncing marketplace orders
    Cleaning old records
    Generating reports
    Running maintenance tasks


## Common Commands to Remember

    php artisan serve
    php artisan route:list

    php artisan make:model Product
    php artisan make:model Product -m
    php artisan make:controller ProductController
    php artisan make:middleware CheckAdmin
    php artisan make:request StoreProductRequest
    php artisan make:job SyncOrders
    php artisan make:event OrderPlaced
    php artisan make:listener SendOrderEmail

    php artisan migrate
    php artisan migrate:rollback
    php artisan migrate:fresh

    php artisan queue:work

    php artisan tinker

    php artisan cache:clear
    php artisan config:clear
    php artisan config:cache
    php artisan optimize:clear

    php artisan storage:link

    php artisan test

    php artisan down
    php artisan up


## Remember

Artisan is Laravel's CLI tool.

Think of it as the main command-line interface for managing a Laravel application.

The most important categories are:

    Create
    → make:model, make:controller, make:job, etc.

    Database
    → migrate, rollback, fresh

    Debug / Inspect
    → route:list, tinker

    Cache
    → cache:clear, config:clear, optimize:clear

    Queue
    → queue:work

    Testing
    → test

    Deployment / Maintenance
    → down, up

Understand the common commands and know that Artisan provides commands for managing almost every major part of a Laravel application.