# Events

## Overview

An **Event** represents something that happened in the application.

Examples:

- `OrderCreated`
- `UserRegistered`
- `PaymentCompleted`
- `ProductUpdated`

An Event normally does not perform the actual work. It **announces that something happened**.

Something happens
       ↓
Event is dispatched
       ↓
Listeners react
       ↓
Actions are performed

## Why Use Events?

Suppose an order is created.
Several actions may need to happen:

Order Created
      ↓
OrderCreated Event
      ↓
 ┌───────────────┬────────────────┐
 ↓               ↓                ↓
Send Email    Update Stock    Send Notification

Without Events, the order code may need to directly call all these operations.

With Events, the order code only announces that the order was created. Different Listeners can handle the required actions.

This keeps different parts of the application **loosely coupled**.

## Creating an Event

Create an Event using Artisan:

```php
php artisan make:event OrderCreated
```

Laravel creates:
app/Events/OrderCreated.php

Example:

```php
class OrderCreated
{
    public function __construct(
        public Order $order
    ) {}
}
```

The Event can carry data related to what happened.
Here, the created `Order` is stored inside the Event.

## Dispatching an Event

After creating an order:

```php
$order = Order::create($data);
OrderCreated::dispatch($order);
```

This announces:
OrderCreated happened

The Event itself does not normally perform the follow-up work. Listeners respond to it.

## Event Flow

Something happens
       ↓
Event is dispatched
       ↓
Listeners receive Event
       ↓
Listeners perform actions

Example:

Order Created
      ↓
OrderCreated Event
      ↓
 ┌───────────────┬────────────────┐
 ↓               ↓                ↓
Send Email    Update Stock    Notification

## One Event, Multiple Listeners

One Event can have multiple Listeners.

For example:

OrderCreated
      ↓
 ┌────┼───────────────┐
 ↓    ↓               ↓
Email Stock       Notification

Each Listener can have its own responsibility.
This is one of the main benefits of Events.

## Event vs Job

An **Event** describes something that happened.
A **Job** represents a task that needs to be performed.

Event
→ "Order was created."

Job
→ "Send the order confirmation email."

An Event can also trigger a Job through a Listener.

Example:

Order Created
      ↓
OrderCreated Event
      ↓
Listener
      ↓
SendOrderConfirmation Job
      ↓
Queue
      ↓
Email sent

## Event vs Listener

An Event says:
"Something happened."

A Listener says:
"Do something when that happens."

Example:

OrderCreated
     ↓
SendOrderConfirmation

Here:

OrderCreated
→ Event

SendOrderConfirmation
→ Listener

## When Should You Use Events?

Events are useful when different parts of the application need to react to the same action.

Example:

UserRegistered
      ↓
 ┌───────────────┬────────────────┐
 ↓               ↓                ↓
Send Email    Create Profile   Notification

Another example:

PaymentCompleted
      ↓
 ┌───────────────┬────────────────┐
 ↓               ↓                ↓
Update Order   Send Receipt    Notification

## Events and Queues

Events and Queues can work together.

For example:

Order Created
      ↓
OrderCreated Event
      ↓
Listener
      ↓
Dispatch Job
      ↓
Queue
      ↓
Queue Worker
      ↓
Task processed

This allows the application to react to an Event while moving slow work into the background.

## Important Concept

Events help reduce direct dependencies between different parts of the application.

Without Events:

OrderController
      ↓
Send Email
      ↓
Update Stock
      ↓
Create Shipment
      ↓
Send Notification

With Events:

OrderController
      ↓
OrderCreated Event
      ↓
Listeners handle their own responsibilities

The code that creates the order does not need to know every action that should happen afterward.

## Key Points

- An Event represents something that happened.
- Events can carry data.
- Events are dispatched when something happens.
- Listeners respond to Events.
- One Event can have multiple Listeners.
- Events help keep application components loosely coupled.
- Events can trigger Jobs for background processing.

## Interview Questions

### What is an Event in Laravel?

An Event is a class that represents something that happened in the application and allows other parts of the application to react to it through Listeners.

### Why use Events?

Events help separate the code that performs an action from the code that needs to react to that action.

### Can one Event have multiple Listeners?

Yes. One Event can have multiple Listeners, and each Listener can perform a different responsibility.

### Event vs Job?

```text
Event
→ Something happened.

Job
→ A task that needs to be performed.
```

### Event vs Listener?

```text
Event
→ Announces what happened.

Listener
→ Defines what should happen in response.
```