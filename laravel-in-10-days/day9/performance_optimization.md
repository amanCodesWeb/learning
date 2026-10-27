# Laravel Performance Optimization

## Definition

**Performance Optimization** means improving application speed, reducing resource usage, and handling more requests efficiently.

Common goals:
- Faster response times
- Fewer database queries
- Lower memory usage
- Better scalability

## 1. Avoid N+1 Queries

N+1 occurs when Laravel runs one query for a list and then additional queries for each item's relationship.

Bad:

    $orders = Order::all();

    foreach ($orders as $order) {
        echo $order->customer->name;
    }

Better:

    $orders = Order::with('customer')->get();

Eager loading retrieves related data more efficiently.

## 2. Select Only Required Columns

Instead of retrieving every column:

    $users = User::all();

Retrieve only what you need:

    $users = User::select('id', 'name', 'email')->get();

This reduces unnecessary data transfer and memory usage.

## 3. Use Pagination

Avoid loading thousands of records at once.

    $products = Product::paginate(20);

For large datasets, consider cursor pagination:

    $products = Product::orderBy('id')
        ->cursorPaginate(50);

## 4. Use Database Indexes

Indexes help the database find matching records faster.

Example migration:

    $table->index('email');

For frequently searched columns:

    User::where('email', $email)->first();

Choose indexes based on actual query patterns. Unnecessary indexes increase storage and write overhead.

## 5. Cache Expensive Queries

Cache frequently requested data:

    $products = Cache::remember(
        'featured_products',
        600,
        fn () => Product::where('featured', true)->get()
    );

Invalidate or refresh the cache when the underlying data changes.

## 6. Use `chunk()` or `lazy()` for Large Datasets

Avoid loading a huge table into memory.

Using chunks:

    User::chunk(500, function ($users) {
        foreach ($users as $user) {
            // Process each user
        }
    });

Using lazy iteration:

    foreach (User::lazy() as $user) {
        // Process one model at a time
    }

For large jobs that update records, consider `chunkById()` or `lazyById()` when appropriate.

## 7. Use Queue Jobs

Move slow tasks out of the web request when they do not need to finish before the response.

Examples:
- Sending emails
- Generating reports
- Processing large files
- Calling slow third-party APIs

    SendInvoiceEmail::dispatch($order);

A queue worker processes the job separately.

## 8. Optimize Eloquent Queries

Use aggregate queries instead of loading all records just to count them.

Less efficient:

    $count = Order::all()->count();

Better:

    $count = Order::count();

Use `exists()` when you only need to check for a matching record:

    $exists = User::where('email', $email)->exists();

## 9. Production Optimization

Laravel provides optimization commands:

    php artisan optimize

This prepares common framework caches for production.

You can also use:

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

After changing configuration or routes, rebuild the relevant caches. Avoid caching configuration while relying on runtime `.env` calls outside configuration files.

## 10. Measure Before Optimizing

Useful approaches:
- Laravel logs
- Database query logs
- Laravel Telescope in suitable environments
- Application performance monitoring
- Database `EXPLAIN` plans

Identify the actual bottleneck before changing code.

## 11. Common Performance Mistakes

Avoid:
- N+1 queries
- Loading unnecessary columns
- Fetching entire tables unnecessarily
- Missing indexes on important queries
- Repeating expensive calculations
- Performing slow tasks synchronously when they can be queued
- Caching data without an invalidation strategy

## Key Points

- Eager load relationships to avoid N+1 queries.
- Use pagination for large result sets.
- Select only required columns.
- Add appropriate database indexes.
- Cache frequently requested expensive results.
- Use chunking or lazy iteration for large datasets.
- Use queues for suitable background tasks.
- Optimize only after measuring performance.

## Interview Questions

**What is the N+1 problem?**

One query loads the main records, then additional queries load relationships for each record.

**How do you solve N+1?**

Use eager loading:

    Order::with('customer')->get();

**Why use database indexes?**

They can speed up searches and joins, though they add storage and write overhead.

**Why use queues for performance?**

They allow slow tasks to run outside the main web request.

**How do you process millions of records?**

Use chunking, lazy iteration, suitable indexes, and background jobs rather than loading everything into memory.

**What does `php artisan optimize` do?**

It prepares common Laravel framework caches to improve production performance.