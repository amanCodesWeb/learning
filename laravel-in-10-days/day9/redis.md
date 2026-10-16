# Redis

## Definition

**Redis** is an in-memory data store that keeps data in RAM, allowing very fast data access.

Laravel commonly uses Redis for:
- Caching
- Queues
- Sessions
- Rate limiting
- Temporary data storage

## How Redis Works

    Laravel Application
           ↓
          Redis
           ↓
       RAM (Memory)

Redis provides fast access because data is primarily served from memory rather than repeatedly queried from a database.

## Redis vs Database

**Database (MySQL):**
- Stores permanent application records.
- Used for users, orders, products, and payments.

**Redis:**
- Commonly stores temporary or frequently accessed data.
- Used for cache, queues, and sessions.

Redis can also persist data to disk, depending on its configuration.

## Redis in Laravel

Laravel supports Redis through its Redis integration and cache configuration.

Configure Redis connection settings in:

    config/database.php

Cache store configuration is in:

    config/cache.php

Environment variables commonly include:

    REDIS_CLIENT=phpredis
    REDIS_HOST=127.0.0.1
    REDIS_PORT=6379

The required client/package and configuration depend on your Laravel version and environment.

## Using Redis for Cache

Configure Redis as your cache store, then use Laravel's Cache facade:

    Cache::store('redis')->put('products', $products, 600);

Retrieve the cached data:

    $products = Cache::store('redis')->get('products');

Using Redis does not require changing the rest of your cache logic.

## Redis for Queues

Redis can store queued jobs until a worker processes them.

Configure:

    QUEUE_CONNECTION=redis

Dispatch a job:

    SendOrderNotification::dispatch($order);

Start a worker:

    php artisan queue:work redis

Flow:

    Laravel Dispatches Job
            ↓
        Redis Queue
            ↓
        Queue Worker
            ↓
        Job Executes

## Redis for Sessions

Redis can store session data instead of files or a database.

Depending on your Laravel version, configure:

    SESSION_DRIVER=redis

This can be useful when running multiple application servers that need to share session data.

## Redis Data Structures

Redis supports several data structures:

- Strings — simple values and counters.
- Lists — ordered collections of values.
- Sets — unique values.
- Hashes — field-value pairs.
- Sorted sets — values ordered by score.

Laravel abstracts many common operations, so you often use its Cache, Queue, and Session APIs instead of interacting with Redis directly.

## When to Use Redis

Use Redis when you need:
- Fast caching for frequently requested data.
- A backend for queued jobs.
- Shared session storage.
- Fast counters or temporary data.
- Rate-limiting support.

For example, an e-commerce application can cache product listings in Redis while keeping the actual product records in MySQL.

## Important Points

- Redis is an in-memory data store.
- It is commonly used as a Laravel cache and queue backend.
- Redis and MySQL serve different purposes.
- Redis can persist data, but persistence depends on configuration.
- Cache expiration and invalidation still matter.
- Critical business records should not rely solely on temporary cache entries.

## Interview Questions

**What is Redis?**

An in-memory data store commonly used for fast caching, queues, and session storage.

**Why is Redis fast?**

It primarily serves data from memory, reducing storage access overhead.

**Can Laravel use Redis for queues?**

Yes. Configure the Redis queue connection and run a queue worker.

**Redis vs MySQL?**

MySQL is commonly used for persistent relational records; Redis is commonly used for fast in-memory operations.

**Can Redis replace MySQL?**

Not automatically. They have different purposes, and the correct choice depends on the application's data and reliability requirements.