# Laravel Cache

## Definition

**Caching** stores frequently used data temporarily so Laravel can retrieve it faster instead of repeatedly querying the database or recalculating results.

Example:

    Database Query → Cache Result → Reuse Result

Benefits:
- Faster response times
- Reduced database load
- Better application performance

## Basic Cache Usage

Import the facade:

    use Illuminate\Support\Facades\Cache;

Store data:

    Cache::put('username', 'Aman', 600);

`600` is the expiration time in seconds (10 minutes).

Retrieve data:

    $name = Cache::get('username');

Set a default value:

    $name = Cache::get('username', 'Guest');

Check existence:

    if (Cache::has('username')) {
        // Cache exists
    }

Remove data:

    Cache::forget('username');

Clear the entire cache store:

    Cache::flush();

Use `flush()` carefully because it can remove all entries in the selected cache store.

## Remember: Get or Store

`Cache::remember()` returns cached data if available. Otherwise, it executes the callback, stores the result, and returns it.

    $products = Cache::remember('products', 600, function () {
        return Product::all();
    });

Flow:

    Request
       ↓
    Check Cache
       ↓
    Cache Hit? ── Yes → Return Cached Data
       |
       No
       ↓
    Query Database
       ↓
    Store Result in Cache
       ↓
    Return Data

## Cache Forever

Store data without automatic expiration:

    Cache::forever('site_name', 'OneBasket');

Remove it when no longer needed:

    Cache::forget('site_name');

## Cache Tags

Some cache drivers support tags, allowing related entries to be grouped.

    Cache::tags(['products'])->put('featured', $products, 600);

Clear that group:

    Cache::tags(['products'])->flush();

**Note:** Cache tags are not supported by every driver, including the database and file cache drivers.

## Cache Drivers

The cache driver defines where cached data is stored.

Common drivers:

- `file` — Stores cache in files.
- `database` — Stores cache in database tables.
- `redis` — Stores cache in Redis.
- `array` — Stores cache in memory for the current request/process; useful for testing.

Configure the cache store in:

    config/cache.php

The default can be set through the environment configuration:

    CACHE_STORE=file

The supported drivers and configuration depend on your Laravel version.

## Cache Invalidation

When underlying data changes, cached data may become outdated. Removing or refreshing it is called **cache invalidation**.

Example:

    public function update(Product $product)
    {
        $product->update([
            'name' => 'Updated Product',
        ]);

        Cache::forget('products');
    }

The next request can rebuild the cached product list.

## Cache vs Session

Cache:
- Stores data to improve performance.
- Usually shared across requests.
- Data may expire or be cleared.

Session:
- Stores user-specific state across requests.
- Commonly used for login state and temporary user data.

## Cache vs Database

Database:
- Main persistent data storage.
- Used for reliable application records.

Cache:
- Faster temporary storage.
- Can be cleared and rebuilt from the source data.

Do not treat cache as the only source of important business data.

## Key Points

- Cache reduces repeated database queries and calculations.
- `Cache::put()` stores data.
- `Cache::get()` retrieves data.
- `Cache::remember()` retrieves or calculates and stores data.
- `Cache::forget()` removes an entry.
- Cache invalidation prevents stale data.
- Redis is a common cache backend.
- Choose expiration times based on how frequently data changes.

## Interview Questions

**What is caching?**

Temporarily storing frequently used data to improve performance and reduce repeated work.

**What is a cache hit?**

When requested data is found in the cache.

**What is a cache miss?**

When data is not found in the cache and must be retrieved or calculated.

**What does `Cache::remember()` do?**

It returns cached data or executes a callback and stores the result when the cache is missing.

**What is cache invalidation?**

Removing or refreshing cached data when the underlying source changes.

**Cache vs Session?**

Cache improves performance by reusing data; sessions maintain user-specific state across requests.