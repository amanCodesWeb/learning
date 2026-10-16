# Database Transactions

## Definition

A **Database Transaction** is a group of database operations treated as one logical unit.

Either all operations succeed, or the transaction is rolled back so partial changes are not committed.

Example: transferring money between two accounts.

    Account A: -500
    Account B: +500

Both operations must succeed. If one fails, neither change should remain committed.

## ACID Properties

Transactions follow four important principles:

- **Atomicity:** All operations succeed or none are committed.
- **Consistency:** Data remains valid according to database rules.
- **Isolation:** Concurrent transactions are controlled to prevent interference.
- **Durability:** Committed changes persist according to database guarantees.

## Basic Laravel Transaction

Use the `DB` facade:

    use Illuminate\Support\Facades\DB;

    DB::transaction(function () {
        Account::where('id', 1)->decrement('balance', 500);
        Account::where('id', 2)->increment('balance', 500);
    });

Laravel commits the transaction if the callback succeeds. If an exception occurs, it rolls back the transaction and rethrows the exception.

## Manual Transaction

You can control the transaction manually:

    DB::beginTransaction();

    try {
        // Database operations

        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();

        throw $e;
    }

- `beginTransaction()` starts a transaction.
- `commit()` saves the changes.
- `rollBack()` cancels uncommitted changes.

Use `try/catch` when you need custom error handling.

## Practical Example: Creating an Order

Suppose placing an order requires:
1. Creating an order.
2. Creating order items.
3. Updating inventory.

These database operations should succeed or fail together.

    DB::transaction(function () use ($request) {
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $request->total,
        ]);

        $order->items()->createMany(
            $request->items
        );

        // Update inventory here.
    });

If a database operation throws an exception, the transaction rolls back its uncommitted database changes.

**Important:** Validate and authorize the request before processing it. External actions such as sending emails or calling payment APIs are not automatically rolled back with a database transaction.

## Transaction with Retry Attempts

Laravel can retry a transaction after certain concurrency-related database failures:

    DB::transaction(function () {
        // Database operations
    }, 3);

The second argument specifies the maximum number of attempts.

Keep transaction callbacks safe to retry. Avoid directly sending emails or making external API calls inside a callback that may execute more than once.

## Transaction vs Rollback

    Begin Transaction
          ↓
    Execute Queries
          ↓
    All Successful?
       ↙       ↘
     Yes        No
      ↓          ↓
    Commit     Rollback

## Important Points

- Use transactions when multiple database changes must succeed together.
- `DB::transaction()` automatically commits or rolls back.
- Manual transactions use `beginTransaction()`, `commit()`, and `rollBack()`.
- Transactions cannot undo external API calls or emails.
- Keep transactions short to reduce lock contention.
- Database transactions generally apply to operations on the same transactional database connection.

## Interview Questions

**What is a database transaction?**

A group of database operations treated as one logical unit.

**What are ACID properties?**

Atomicity, Consistency, Isolation, and Durability.

**What happens if an exception occurs inside `DB::transaction()`?**

Laravel rolls back the uncommitted transaction and rethrows the exception.

**When should you use a transaction?**

When related database operations must either all succeed or all be rolled back.

**Can a transaction roll back an API request?**

No. It can roll back database changes, but it cannot automatically undo an external API call.

**`commit()` vs `rollBack()`?**

`commit()` saves the transaction's changes; `rollBack()` cancels its uncommitted changes.