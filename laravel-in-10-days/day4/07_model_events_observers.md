# Model Events & Observers

## Definition

**Model Events** are events automatically triggered when something happens to an Eloquent model.

Examples:

    creating
    created
    updating
    updated
    saving
    saved
    deleting
    deleted
    restoring
    restored

**Observer** is a separate class used to organize the logic for these model events.

Simple idea:

    Model Action
         ↓
    Model Event
         ↓
    Observer
         ↓
    Custom Logic

---

## Why Use Model Events?

Model events are useful when some logic should automatically run when a model changes.

Examples:

- Generate a slug when creating a product
- Send an action after an order is created
- Clear cache after updating a product
- Create an audit record after deletion
- Perform cleanup when a model is deleted

Instead of putting this logic everywhere:

    Controller
    Job
    Command
    Service

You can keep model-specific event logic in one place.

---

## Common Model Events

### Creating

Triggered before a new model is inserted.
    creating

### Created

Triggered after a new model has been inserted.
    created

### Updating

Triggered before an existing model is updated.
    updating

### Updated

Triggered after an existing model is updated.
    updated

### Saving

Triggered before creating or updating.
    saving

### Saved

Triggered after creating or updating.
    saved

### Deleting

Triggered before a model is deleted.
    deleting

### Deleted

Triggered after deletion.
    deleted

### Restoring / Restored

Used with SoftDeletes.
    restoring
    restored

---

## Event Timing

    saving
       ↓
    creating / updating
       ↓
    Database Operation
       ↓
    created / updated
       ↓
    saved

For deletion:

    deleting
       ↓
    Database Delete
       ↓
    deleted

---

## Model Events Directly in Model

You can register events inside the model.

Example:

    protected static function booted(): void
    {
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

Now whenever a Product is created:

    Product::create([
        'name' => 'Red Shirt',
    ]);

Laravel automatically generates the slug.

---

## What is an Observer?

An **Observer** is a class that contains methods for handling model events.
Instead of keeping many event callbacks inside the model, you move them into an Observer.

Example:

    ProductObserver

It can contain:

    creating()
    created()
    updating()
    updated()
    deleting()
    deleted()

---

## Creating an Observer

Command:
    php artisan make:observer ProductObserver --model=Product

Laravel creates:
    app/Observers/ProductObserver.php

Example:

    class ProductObserver
    {
        public function creating(Product $product): void
        {
            $product->slug = Str::slug($product->name);
        }

        public function deleted(Product $product): void
        {
            // Cleanup logic
        }
    }

---

## Registering an Observer

Register the observer with the model.

Example:

    use App\Models\Product;
    use App\Observers\ProductObserver;

    Product::observe(ProductObserver::class);

After registration, Laravel automatically calls the observer methods when Product events occur.

---

## Observer Example

    class OrderObserver
    {
        public function created(Order $order): void
        {
            // Send notification
            // Update related data
            // Dispatch a job
        }

        public function deleted(Order $order): void
        {
            // Cleanup logic
        }
    }

Flow:

    Order::create()
         ↓
    created event
         ↓
    OrderObserver@created()
         ↓
    Custom logic

---

## Observer vs Model Events

### Model Event

The event is the lifecycle action:

    created
    updated
    deleted

### Observer

The class that handles those lifecycle events.

Simple:

    Model Event = What happened?

    Observer = What should we do?

---

## `creating` vs `created`

### `creating`

Runs before the record is inserted.

Useful when you need to modify the model before saving.

    public function creating(Product $product): void
    {
        $product->slug = Str::slug($product->name);
    }

### `created`

Runs after the record has been inserted.

Useful when the database record already exists.

    public function created(Product $product): void
    {
        // Dispatch job
    }

---

## `updating` vs `updated`

### `updating`

Runs before an existing model is updated.

### `updated`

Runs after the update has happened.

Example:

    public function updated(Product $product): void
    {
        Cache::forget('products');
    }

---

## `deleting` vs `deleted`

### `deleting`

Runs before deletion.

Useful when you need to perform checks or prepare cleanup.

### `deleted`

Runs after deletion.

Useful for cleanup or related actions.

---

## Observer with Soft Deletes

Observers can also handle SoftDeletes.

Example:

    public function deleting(User $user): void
    {
        // Logic before soft delete
    }

    public function deleted(User $user): void
    {
        // Logic after soft delete
    }

Restore events:

    restoring
    restored

---

## Important: Events Are Not the Same as Jobs

### Observer

Triggered by a model lifecycle event.

    Product updated
         ↓
    Observer

### Job

Represents work that can be processed synchronously or through a queue.

    Job
      ↓
    Queue
      ↓
    Worker

An observer can dispatch a job:

    public function created(Order $order): void
    {
        SendOrderNotification::dispatch($order);
    }

Flow:

    Order Created
         ↓
    Observer
         ↓
    Job Dispatched
         ↓
    Queue
         ↓
    Worker

---

## When to Use Observers

Use observers when logic is strongly related to the model lifecycle.

Good examples:

- Generate slugs
- Audit model changes
- Clear model cache
- Create related records
- Cleanup related resources
- Dispatch jobs after model changes

Avoid putting large business workflows inside observers.

For complex workflows, use:

    Observer
       ↓
    Service / Job
       ↓
    Business Logic

---

## Important Difference: Observer vs Application Event

### Model Observer

Automatically responds to Eloquent model lifecycle events.

    Product created
         ↓
    ProductObserver

### Application Event

A custom application event that represents something meaningful.

    OrderCreated
         ↓
    Listener
         ↓
    SendNotification

Simple rule:

    Model lifecycle → Observer

    Business/application event → Event + Listener

---

## Key Points

- Model events are triggered during Eloquent model operations.
- Observers organize model-event logic in separate classes.
- `creating` and `updating` run before database changes.
- `created` and `updated` run after database changes.
- `deleting` and `deleted` handle deletion lifecycle.
- `restoring` and `restored` work with SoftDeletes.
- Observers keep models cleaner.
- Observers can dispatch Jobs or application Events.
- Avoid putting large business logic directly inside observers.

---

## Interview Questions

### What are Model Events?

Lifecycle events automatically triggered when Eloquent models are created, updated, deleted, or restored.

### What is an Observer?

A class that contains handlers for Eloquent model events.

### Why use Observers?

To keep model-event logic organized and separate from the model/controller.

### `creating` vs `created`?

`creating` runs before insertion; `created` runs after insertion.

### `updating` vs `updated`?

`updating` runs before an update; `updated` runs after the update.

### Observer vs Event + Listener?

Observer handles model lifecycle events. Event + Listener is generally used for application/business events.

### Can an Observer dispatch a Job?

Yes.

    public function created(Order $order): void
    {
        SendOrderNotification::dispatch($order);
    }

This is useful when the observer detects a model change but the actual work should be handled asynchronously.