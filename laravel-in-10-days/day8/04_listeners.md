# Listeners

## Overview

A **Listener** contains the logic that should run when a specific **Event** occurs.

- **Event** → Something happened.
- **Listener** → What should happen because of that event.

Example:

OrderCreated Event
       ↓
   Listeners
    ↙  ↓  ↘
 Email Inventory Shipment

---

## Why Use Listeners?

Listeners keep event-related logic separate from the main application logic.

Instead of putting everything inside a Controller:

```php
$order = Order::create($data);

SendEmail::send($order);
UpdateInventory::update($order);
CreateShipment::create($order);
```

We can dispatch an event:

```php
OrderCreated::dispatch($order);
```

Then different listeners handle the required actions.

### Benefits

- Cleaner code
- Loose coupling
- Easier maintenance
- One event can have multiple listeners
- Listeners can be queued
- Business logic stays separated

---

## Creating a Listener

Laravel provides an Artisan command:

```php
php artisan make:listener SendOrderConfirmation --event=OrderCreated
```

This creates a listener associated with the `OrderCreated` event.

---

## Basic Listener

Example Event:

```php
class OrderCreated
{
    public function __construct(
        public Order $order
    ) {}
}
```

Listener:

```php
class SendOrderConfirmation
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Send order confirmation email
    }
}
```

The `handle()` method contains the logic that should execute when the event occurs.

---

## How the Listener Works

When the event is dispatched:

```php
OrderCreated::dispatch($order);
```

Laravel sends the event to its listeners.

The listener receives the event:

```php
public function handle(OrderCreated $event): void
{
    $order = $event->order;
}
```

The listener can then perform the required action.

---

## Multiple Listeners for One Event

One event can have multiple listeners.

OrderCreated
    │
    ├── SendOrderConfirmation
    │
    ├── UpdateInventory
    │
    └── CreateShipment

Each listener handles a different responsibility.

Example:

```php
class UpdateInventory
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Update product stock
    }
}
```

Another listener:

```php
class CreateShipment
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Create shipment
    }
}
```

This keeps each piece of logic focused on one responsibility.

---

## Listener vs Event

| Event | Listener |
|---|---|
| Represents something that happened | Handles what should happen |
| Contains event data | Contains response logic |
| Example: `OrderCreated` | Example: `SendOrderConfirmation` |
| Can have multiple listeners | Handles a specific event |

Simple way to remember:

Event = What happened?
Listener = What should we do?

---

## Listener vs Job

A **Listener** responds to an event.

A **Job** represents a task that can be executed, often through a queue.

Event
  ↓
Listener
  ↓
Job
  ↓
Queue

For example:

OrderCreated
     ↓
SendOrderConfirmation Listener
     ↓
SendConfirmationEmail Job
     ↓
Queue

A listener can also perform the work directly if the operation is small and does not need to be queued.

---

## Queued Listeners

Listeners can implement `ShouldQueue` when the work should run asynchronously.

```php
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmation implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Send email
    }
}
```

Now Laravel sends the listener to the queue instead of executing the work immediately.

This is useful for slow operations such as:

- Sending emails
- Calling third-party APIs
- Creating shipments
- Processing large amounts of data
- Sending notifications

---

## Example: E-Commerce Order

Suppose an order is created:

```php
OrderCreated::dispatch($order);
```

Multiple listeners can respond:

                    OrderCreated
                         │
          ┌──────────────┼──────────────┐
          ↓              ↓              ↓
   SendConfirmation  UpdateInventory  CreateShipment
          │              │              │
          ↓              ↓              ↓
        Email         Stock Update    Shipping API

The Controller does not need to know how each operation is implemented.

---

## Event Flow

User places order
       ↓
Order is created
       ↓
OrderCreated Event
       ↓
Laravel finds Listeners
       ↓
Listeners execute
       ↓
Required actions happen

If listeners implement `ShouldQueue`:

OrderCreated
     ↓
Listener
     ↓
Queue
     ↓
Queue Worker
     ↓
Listener executes

---

## When to Use Listeners

Use listeners when:

- An action should happen because of an event
- Multiple actions should respond to the same event
- You want to separate responsibilities
- You want loose coupling
- The action can be handled asynchronously

Examples:

UserRegistered
    → SendWelcomeEmail

OrderCreated
    → SendConfirmation
    → UpdateInventory
    → CreateShipment

PaymentCompleted
    → UpdateOrderStatus
    → SendReceipt

ProductUpdated
    → ClearCache
    → SyncMarketplace

---

## Key Points

- A Listener responds to an Event.
- The `handle()` method contains the listener logic.
- One Event can have multiple Listeners.
- Listeners help achieve loose coupling.
- Listeners can implement `ShouldQueue`.
- Queued listeners are useful for slow or external operations.
- Event = what happened.
- Listener = what should happen next.

---

## Interview Questions

### 1. What is a Listener in Laravel?

A Listener handles the logic that should execute when a specific Event occurs.

### 2. What is the difference between an Event and Listener?

An Event represents something that happened, while a Listener contains the logic that responds to that event.

### 3. Can one Event have multiple Listeners?

Yes. Multiple listeners can respond to the same event.

### 4. Can a Listener be queued?

Yes. A Listener can implement `ShouldQueue` so that it runs asynchronously through Laravel's queue system.

### 5. Why use Listeners?

Listeners separate event-handling logic from the main application flow and help keep the code loosely coupled and maintainable.