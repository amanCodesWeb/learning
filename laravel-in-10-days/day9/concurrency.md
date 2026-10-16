# Concurrency in Laravel

## Definition

**Concurrency** means multiple requests or processes access or modify data at the same time.

Example: Two customers try to purchase the last available product simultaneously.

Without proper protection, both requests might see stock as `1` and both attempt to purchase it, causing incorrect inventory.

## Common Concurrency Problems

- **Race Condition:** The result depends on the timing of concurrent operations.
- **Lost Update:** One update overwrites another update.
- **Duplicate Processing:** The same task or request is processed more than once.
- **Deadlock:** Transactions wait on each other's locked resources.

## 1. Database Transactions

Transactions group related database operations together.

    DB::transaction(function () {
        // Related database operations
    });

Transactions help maintain consistency, but they do not automatically prevent every race condition.

## 2. Pessimistic Locking

Pessimistic locking locks selected database rows while a transaction processes them.

Example: Prevent selling more stock than available.

    DB::transaction(function () use ($productId) {
        $product = Product::where('id', $productId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($product->stock < 1) {
            throw new RuntimeException('Out of stock');
        }

        $product->decrement('stock');
    });

`lockForUpdate()` locks the selected row against conflicting updates until the transaction completes.

**Important:** Use it inside a transaction and on a database that supports the required row-locking behavior.

## 3. Optimistic Locking

Optimistic locking assumes conflicts are uncommon. Before updating, the application checks whether the record has changed since it was read.

A common approach uses a version column.

    $updated = Product::where('id', $id)
        ->where('version', $version)
        ->update([
            'stock' => $newStock,
            'version' => $version + 1,
        ]);

If `$updated` is `0`, another process may have changed the record. Reload the data and handle the conflict.

The `version` column must exist, and the application must manage it consistently.

## 4. Atomic Database Updates

Prefer atomic database operations over reading and then writing values separately.

Riskier approach:

    $product->stock = $product->stock - 1;
    $product->save();

Safer atomic update:

    Product::where('id', $id)
        ->where('stock', '>', 0)
        ->decrement('stock');

For reliable inventory handling, also check whether a row was actually updated:

    $updated = Product::where('id', $id)
        ->where('stock', '>', 0)
        ->decrement('stock');

    if (! $updated) {
        // Product is unavailable or out of stock
    }

## 5. Cache Locks

Laravel supports atomic locks through compatible cache drivers.

Example:

    use Illuminate\Support\Facades\Cache;

    $lock = Cache::lock('process-order-' . $orderId, 10);

    if ($lock->get()) {
        try {
            // Process the order
        } finally {
            $lock->release();
        }
    }

This helps prevent multiple processes from executing the same protected section simultaneously.

Use a shared lock-capable cache backend when multiple application servers are involved. Choose appropriate lock expiration and failure handling.

## 6. Idempotency

**Idempotency** means repeating the same operation does not create additional unintended effects.

Example: A payment provider retries a webhook.

Without protection:

    Webhook Retry
         ↓
    Two Payments / Orders

With idempotency:

    Webhook Retry
         ↓
    Check Unique Event or Key
         ↓
    Already Processed?
         ↓
    Avoid Duplicate Processing

Use unique database constraints and persist processed event IDs or idempotency keys. Checking a value without enforcing uniqueness can itself have race conditions.

## 7. Queues and Concurrency

Multiple queue workers can process jobs simultaneously.

This improves throughput but introduces possible duplicate processing and shared-data conflicts.

Useful protections include:
- Idempotent jobs
- Unique database constraints
- Database transactions and row locks
- Cache locks when appropriate
- Retry and failure handling

A queue does not automatically guarantee that a job executes only once.

## Concurrency vs Transactions vs Locks

- **Concurrency:** Multiple operations run at the same time.
- **Transaction:** Groups database operations into one logical unit.
- **Lock:** Controls access to a resource while work is performed.
- **Idempotency:** Prevents repeated requests from causing duplicate effects.

## Key Points

- Race conditions can cause incorrect data.
- Transactions help maintain database consistency.
- `lockForUpdate()` provides pessimistic row locking.
- Optimistic locking detects conflicting updates.
- Atomic updates reduce read-modify-write race conditions.
- Cache locks coordinate access across processes when configured correctly.
- Idempotency is essential for payment callbacks and webhook retries.
- Always handle lock timeouts, transaction failures, and retry behavior.

## Interview Questions

**What is concurrency?**

Multiple requests or processes execute or access shared resources at the same time.

**What is a race condition?**

A problem where the result depends on the timing of concurrent operations.

**What is `lockForUpdate()`?**

A Laravel query method that requests a lock on selected rows, typically inside a transaction.

**What is optimistic locking?**

A technique that detects whether a record changed before applying an update.

**Do transactions prevent all race conditions?**

No. Depending on the isolation level and operation, additional locking, atomic updates, or constraints may be required.

**How do you prevent duplicate payment processing?**

Use idempotency keys or unique event IDs, enforce database uniqueness, and make processing safe to retry.