# Notifications

## Overview

Laravel **Notifications** provide a convenient way to send messages to users through different channels.

Common notification channels include:

- Mail
- Database
- Broadcast
- Slack
- SMS or other channels through custom integrations

Example:

Order Shipped
      ↓
Notification
      ↓
┌──────────┬───────────┐
│   Email  │  Database │
└──────────┴───────────┘

---

## Why Use Notifications?

Instead of writing notification logic separately in different places, Laravel provides a centralized Notification class.

For example:

```php
$user->notify(new OrderShipped($order));
```

The Notification class decides what message should be sent and through which channels.

Benefits:

- Centralized notification logic
- Supports multiple channels
- Easy to reuse
- Can be queued
- Keeps application code clean

---

## Creating a Notification

Create a notification using Artisan:

```php
php artisan make:notification OrderShipped
```

Laravel creates a Notification class.

Example:

```php
use Illuminate\Notifications\Notification;

class OrderShipped extends Notification
{
    public function __construct(
        public Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }
}
```

---

## Sending a Notification

A model that receives notifications is called the **notifiable model**.
Usually, the `User` model uses Laravel's `Notifiable` trait:

```php
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;
}
```

Then send the notification:

```php
$user->notify(new OrderShipped($order));
```

Flow:

User
 ↓
notify()
 ↓
OrderShipped Notification
 ↓
Notification Channel
 ↓
User receives notification

---

## Notification Channels

The `via()` method determines which channels should be used.

```php
public function via(object $notifiable): array
{
    return ['mail'];
}
```

Multiple channels can be returned:

```php
public function via(object $notifiable): array
{
    return ['mail', 'database'];
}
```

The same notification can therefore be delivered through multiple channels.

---

## Mail Notifications

For email notifications, use the `mail` channel:

```php
public function via(object $notifiable): array
{
    return ['mail'];
}
```

Then define the email:

```php
use Illuminate\Notifications\Messages\MailMessage;

public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Order Shipped')
        ->line('Your order has been shipped.')
        ->action('Track Order', url('/orders/' . $this->order->id));
}
```

Example result:

Order Shipped
Your order has been shipped.
[Track Order]

---

## Database Notifications

Notifications can also be stored in the database.

Use the `database` channel:

```php
public function via(object $notifiable): array
{
    return ['database'];
}
```

Then define the database data:

```php
public function toDatabase(object $notifiable): array
{
    return [
        'order_id' => $this->order->id,
        'message' => 'Your order has been shipped.',
    ];
}
```

The notification data is stored so the application can display it later.

Example:

User Dashboard
🔔 Your order #1024 has been shipped.

---

## Database Notification Migration

Laravel provides a command for creating the notification table migration:

```php
php artisan make:notifications-table
```

Then run:

```php
php artisan migrate
```

This creates the table used to store database notifications.

---

## Reading Database Notifications

A user can access their notifications through the `notifications` relationship:

```php
$user->notifications;
```

Unread notifications can be accessed using:

```php
$user->unreadNotifications;
```

After a notification has been read:

```php
$notification->markAsRead();
```

---

## Multiple Channels

A notification can use different channels at the same time.

```php
public function via(object $notifiable): array
{
    return ['mail', 'database'];
}
```

Flow:

Order Shipped
      ↓
Notification
      ↓
┌──────────────┬──────────────┐
│     Mail     │   Database   │
│              │              │
│ Email sent   │ Stored in DB │
└──────────────┴──────────────┘

---

## Conditional Channels

The channels can depend on the user or application state.

```php
public function via(object $notifiable): array
{
    $channels = ['database'];

    if ($notifiable->email) {
        $channels[] = 'mail';
    }

    return $channels;
}
```

This allows notifications to adapt to the recipient.

---

## Queued Notifications

Notifications can be queued when sending them immediately would slow down the request.

Implement `ShouldQueue`:

```php
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Notification implements ShouldQueue
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }
}
```

Now Laravel can process the notification through the queue.

Flow:

User Action
     ↓
Send Notification
     ↓
Queue
     ↓
Queue Worker
     ↓
Email / Other Channel

This is useful for notifications that involve slow operations.

---

## Notification vs Mail

### Notification

Designed to support multiple notification channels:

Notification
 ├── Mail
 ├── Database
 ├── Broadcast
 └── Other channels

### Mail

Specifically handles email messages.
Use Notifications when the same message may need to be delivered through different channels.

---

## Notification vs Event

An Event represents something that happened.
A Notification is a message delivered to a user.

Example:

OrderCreated Event
       ↓
Listener
       ↓
OrderShipped Notification
       ↓
Email / Database

More commonly:

OrderShipped Event
       ↓
Listener
       ↓
Send Notification

---

## Notification vs Job

A Notification represents the message being sent.
A Job represents a task that should be executed.

They can work together:

Event
  ↓
Listener
  ↓
Notification
  ↓
Queue
  ↓
User receives notification

---

## Example: Order Status Notification

Suppose an order changes to `shipped`.

The application can send:

```php
$user->notify(new OrderShipped($order));
```

The Notification can send the message through email and database:

```php
public function via(object $notifiable): array
{
    return ['mail', 'database'];
}
```

Flow:

Order Status = Shipped
        ↓
OrderShipped Notification
        ↓
┌──────────────┬──────────────┐
│     Mail     │   Database   │
└──────────────┴──────────────┘
        ↓              ↓
      Email       Dashboard

---

## When to Use Notifications

Use Notifications when:
- Users need to be informed about an action
- You need multiple delivery channels
- You want centralized notification logic
- Notifications should be stored for later viewing
- Notifications should be processed asynchronously

Examples:

Order Shipped
    → Email
    → Database

Payment Successful
    → Email
    → Database

Password Changed
    → Email

New Message
    → Database
    → Broadcast

---

## Key Points

- Notifications are used to inform users.
- Notifications support multiple delivery channels.
- `via()` defines the notification channels.
- `toMail()` defines an email notification.
- `toDatabase()` defines database notification data.
- Users can access stored notifications through the `notifications` relationship.
- `unreadNotifications` returns unread notifications.
- Notifications can implement `ShouldQueue`.
- Notifications and Events have different responsibilities.
- Notifications can work with Events, Listeners, and Queues.

---

## Interview Questions

### 1. What are Notifications in Laravel?

Notifications provide a convenient way to send messages to users through channels such as mail, database, broadcast, and other integrations.

### 2. How do you send a Notification?

```php
$user->notify(new OrderShipped($order));
```

### 3. What does the `via()` method do?

It determines which notification channels should be used.

```php
public function via(object $notifiable): array
{
    return ['mail', 'database'];
}
```

### 4. How do you create a mail notification?

Use the `mail` channel and define the message using `toMail()`.

```php
public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Order Shipped')
        ->line('Your order has been shipped.');
}
```

### 5. Can Notifications be queued?

Yes. A Notification can implement `ShouldQueue` so that it is processed asynchronously.

### 6. What is the difference between Notification and Event?

An Event represents something that happened, while a Notification is a message delivered to a user.

### 7. Can one Notification use multiple channels?

Yes.

```php
public function via(object $notifiable): array
{
    return ['mail', 'database'];
}
```