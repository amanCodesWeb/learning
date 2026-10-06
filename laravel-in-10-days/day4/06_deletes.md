# Delete and Soft Delete in Laravel

## Definition

Laravel supports two common ways to remove records:

- **Hard Delete** → Permanently removes the record from the database.
- **Soft Delete** → Keeps the record in the database but marks it as deleted.

Simple difference:

    Hard Delete
        ↓
    Record is permanently removed

    Soft Delete
        ↓
    Record remains in database
        ↓
    deleted_at = current timestamp

---

## 1. Hard Delete

Delete a model:

    $user = User::find(1);
    $user->delete();

The database record is permanently removed.

You can also delete by ID:

    User::destroy(1);

Multiple records:

    User::destroy([1, 2, 3]);

---

## 2. Delete Using Query Builder

    DB::table('users')
        ->where('id', 1)
        ->delete();

This permanently deletes the matching record.

---

## 3. Soft Deletes

Soft delete does not physically remove the record.

Instead, Laravel stores the deletion time in:

    deleted_at

Example:

    id | name  | deleted_at
    ---|-------|-------------------
    1  | Aman  | NULL
    2  | John  | 2026-10-06 07:00

`NULL` means the record is not deleted.

---

## 4. Enable Soft Deletes

### Migration

Add the `deleted_at` column:

    $table->softDeletes();

This creates:

    deleted_at

### Model

Use the `SoftDeletes` trait:

    use Illuminate\Database\Eloquent\SoftDeletes;

    class User extends Model
    {
        use SoftDeletes;
    }

Now:

    $user->delete();

performs a soft delete.

---

## 5. Querying Soft-Deleted Models

Normal queries automatically exclude soft-deleted records:

    User::all();

Only active records are returned.

To include deleted records:

    User::withTrashed()->get();

To get only deleted records:

    User::onlyTrashed()->get();

---

## 6. Restore a Soft-Deleted Record

Restore one record:

    $user->restore();

Restore multiple records:

    User::onlyTrashed()
        ->where('id', 1)
        ->restore();

After restoring:

    deleted_at = NULL

The record becomes available in normal queries again.

---

## 7. Permanently Delete a Soft-Deleted Record

Use `forceDelete()`:

    $user->forceDelete();

This permanently removes the record from the database.

Example:

    $user = User::onlyTrashed()->find(1);

    $user->forceDelete();

---

## 8. Soft Delete Flow

    $user->delete()
          ↓
    deleted_at = timestamp
          ↓
    Hidden from normal queries
          ↓
    withTrashed()
          ↓
    Can retrieve deleted record
          ↓
    restore()
          ↓
    deleted_at = NULL

Or:

    forceDelete()
          ↓
    Permanently removed

---

## 9. Soft Delete vs Hard Delete

    Hard Delete:
        delete()
        ↓
        Record removed permanently

    Soft Delete:
        delete()
        ↓
        deleted_at updated
        ↓
        Record remains recoverable

    Permanent Delete:
        forceDelete()
        ↓
        Record removed permanently

---

## 10. Important Query Methods

    Model::all()
        Active records only

    Model::withTrashed()->get()
        Active + soft-deleted records

    Model::onlyTrashed()->get()
        Soft-deleted records only

    $model->restore()
        Restore soft-deleted record

    $model->forceDelete()
        Permanently delete record

---

## 11. Relationships and Soft Deletes

If a related model uses SoftDeletes, normal relationship queries also exclude deleted records.

Example:

    $order->customer;

If the customer was soft-deleted, the relationship may return `null`.

To include deleted related models, use `withTrashed()` on the relationship query when appropriate.

---

## 12. When to Use Soft Deletes

Soft deletes are useful when records may need to be recovered or preserved for historical purposes.

Common examples:

- Users
- Products
- Orders
- Categories
- Customers
- Invoices

For example, deleting an order may be a bad idea because its history may be required later.

---

## 13. When to Use Hard Delete

Hard deletion is appropriate when the data should genuinely no longer exist.

Examples:

- Temporary data
- Expired sessions
- Temporary files/records
- Data that must be permanently removed

Use hard deletion carefully because recovery is not possible through Laravel.

---

## 14. Important Difference: `delete()` vs `forceDelete()`

With SoftDeletes enabled:

    $user->delete();

means:

    Soft Delete

While:

    $user->forceDelete();

means:

    Permanent Delete

Without SoftDeletes:

    $user->delete();

permanently deletes the record.

---

## Key Points

- Hard delete permanently removes a database record.
- Soft delete keeps the record and sets `deleted_at`.
- Add `$table->softDeletes()` to the migration.
- Add `SoftDeletes` trait to the model.
- Normal queries exclude soft-deleted records.
- `withTrashed()` includes deleted records.
- `onlyTrashed()` returns only deleted records.
- `restore()` recovers a soft-deleted record.
- `forceDelete()` permanently removes a soft-deleted record.
- Soft deletes are useful when data may need to be recovered.

---

## Interview Questions

### What is Soft Delete?

Soft delete marks a record as deleted using the `deleted_at` column instead of removing it from the database.

### How do you enable Soft Delete?

Migration:

    $table->softDeletes();

Model:

    use SoftDeletes;

### How do you get soft-deleted records?

    User::withTrashed()->get();

### How do you get only deleted records?

    User::onlyTrashed()->get();

### How do you restore a deleted record?

    $user->restore();

### How do you permanently delete a soft-deleted record?

    $user->forceDelete();

### Does `User::all()` return soft-deleted records?

No. Normal Eloquent queries automatically exclude soft-deleted records.

### Why use Soft Deletes instead of deleting records permanently?

It allows records to be recovered and preserves historical data when needed.