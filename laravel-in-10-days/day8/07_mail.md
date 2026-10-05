# Mail

## Overview

Laravel Mail provides a simple way to send emails from your application.

Common use cases:
- Order confirmation
- Welcome email
- Password reset
- Payment receipt
- Shipping updates
- Contact form emails

Laravel uses **Mailable classes** to organize email-related logic.

---

## Creating a Mailable

Create a Mailable using Artisan:

```php
php artisan make:mail OrderConfirmation
```

This creates:

`app/Mail/OrderConfirmation.php`

A Mailable represents an email that can be sent by Laravel.

---

## Basic Mailable

Example:

```php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class OrderConfirmation extends Mailable
{
    public function __construct(
        public $order
    ) {}

    public function build()
    {
        return $this
            ->subject('Order Confirmation')
            ->view('emails.order-confirmation');
    }
}
```

The Mailable defines:

- Email data
- Email subject
- Email view

---

## Email View

Create a Blade email template:

`resources/views/emails/order-confirmation.blade.php`

Example:

```php
<h1>Order Confirmation</h1>
<p>Thank you for your order.</p>
<p>Order ID: {{ $order->id }}</p>
<p>Total: {{ $order->total }}</p>
```

The `$order` variable is available because it was passed to the Mailable.

---

## Sending an Email

Use Laravel's `Mail` facade:

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

Mail::to($order->customer_email)
    ->send(new OrderConfirmation($order));
```

Flow:

Application → `Mail::to()` → Mailable → Blade View → Mail Driver → Recipient

---

## Mail Configuration

Mail settings are usually configured through the `.env` file.

Example:

```php
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Sensitive values such as SMTP usernames and passwords should not be hardcoded in PHP files.

---

## Mail Configuration File

Laravel's mail configuration is located at:

`config/mail.php`

It can use environment variables:

```php
'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
    ],
],
```

This allows different environments to use different mail servers without changing application code.

---

## Passing Data to a Mailable

Data can be passed through the Mailable constructor:

```php
class OrderConfirmation extends Mailable
{
    public function __construct(
        public $order
    ) {}

    public function build()
    {
        return $this
            ->subject('Order Confirmation')
            ->view('emails.order-confirmation');
    }
}
```

Send the order:

```php
Mail::to($order->customer_email)
    ->send(new OrderConfirmation($order));
```

Use the data in the Blade view:

```php
<h1>Order #{{ $order->id }}</h1>
<p>Total: {{ $order->total }}</p>
```

---

## Email Subject

The subject can be defined in the Mailable:

```php
return $this
    ->subject('Your Order Confirmation')
    ->view('emails.order-confirmation');
```

It can also contain dynamic data:

```php
return $this
    ->subject('Order #' . $this->order->id)
    ->view('emails.order-confirmation');
```

---

## HTML Emails

Laravel supports HTML emails using Blade templates.

Example:

```php
<!DOCTYPE html>
<html>
<body>

    <h1>Order Confirmed</h1>
    <p>Hello {{ $order->customer_name }},</p>
    <p>Your order #{{ $order->id }} has been confirmed.</p>
    <p>Total: {{ $order->total }}</p>

</body>
</html>
```

---

## Email Attachments

Files can be attached to emails.

Example:

```php
return $this
    ->subject('Your Invoice')
    ->view('emails.invoice')
    ->attach(
        storage_path('app/invoices/invoice.pdf')
    );
```

Common uses:

- Invoices
- Receipts
- Reports
- Documents

---

## Sending Mail Through a Queue

Sending an email can take time, especially when communicating with an external mail server.

A Mailable can implement `ShouldQueue`:

```php
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmation extends Mailable implements ShouldQueue
{
    public function __construct(
        public $order
    ) {}

    public function build()
    {
        return $this
            ->subject('Order Confirmation')
            ->view('emails.order-confirmation');
    }
}
```

Then queue the email:

```php
Mail::to($order->customer_email)
    ->queue(new OrderConfirmation($order));
```

The email is placed into Laravel's queue instead of being processed immediately.

A queue worker processes it later:

```php
php artisan queue:work
```

---

## Mail + Event + Listener

Mail is often used together with Events and Listeners.

Flow:
OrderCreated → SendOrderConfirmation Listener → OrderConfirmation Mailable → Email

If the listener is queued:
OrderCreated → Queued Listener → Mailable → Queue → Email

This prevents email processing from slowing down the user's request.

---

## Example: Order Confirmation

Event:

```php
OrderCreated::dispatch($order);
```

Listener:

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmation implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        Mail::to($event->order->customer_email)
            ->send(new OrderConfirmation($event->order));
    }
}
```

Mailable:

```php
class OrderConfirmation extends Mailable
{
    public function __construct(
        public $order
    ) {}

    public function build()
    {
        return $this
            ->subject('Order Confirmation')
            ->view('emails.order-confirmation');
    }
}
```

Flow:

User places order  
↓  
OrderCreated Event  
↓  
SendOrderConfirmation Listener  
↓  
OrderConfirmation Mailable  
↓  
Mail Server  
↓  
Customer

---

## Mail vs Notification

### Mail

Mail is specifically used for sending emails.
Application → Mailable → Email

### Notification

Notifications provide a broader system for sending notifications through different channels.

Notification

- Mail
- Database
- Broadcast
- Other channels

Use **Mail** when you need a dedicated email/Mailable.

Use **Notifications** when the same notification may need multiple delivery channels.

---

## Mail vs Queued Mail

### Immediate Mail

```php
Mail::to($email)
    ->send(new OrderConfirmation($order));
```

The email is processed during the current request.

### Queued Mail

```php
Mail::to($email)
    ->queue(new OrderConfirmation($order));
```

The email is added to the queue and processed by a queue worker.

Queued mail is useful when:

- Sending many emails
- Email processing is slow
- The application should respond quickly
- External mail services are involved

---

## Common Mail Flow

Without queue:

User Action → Application Logic → Mailable → Mail Driver → Mail Server → Recipient

With queue:

User Action → Mailable → Queue → Queue Worker → Mail Server → Recipient

---

## Key Points

- Laravel uses **Mailables** to organize email logic.
- Create a Mailable using `php artisan make:mail`.
- Email templates can be created using Blade.
- Use `Mail::to()` to specify the recipient.
- Use `send()` for immediate sending.
- Use `queue()` for asynchronous sending.
- Mail configuration is generally stored through `.env` and `config/mail.php`.
- Data can be passed to a Mailable through its constructor.
- Emails can contain attachments.
- Mail can work together with Events, Listeners, and Queues.
- Notifications provide a broader multi-channel notification system.

---

## Interview Questions

### 1. What is a Mailable in Laravel?

A Mailable is a Laravel class used to define and organize the content and behavior of an email.

### 2. How do you create a Mailable?

```php
php artisan make:mail OrderConfirmation
```

### 3. How do you send an email in Laravel?

```php
Mail::to($email)
    ->send(new OrderConfirmation($order));
```

### 4. Where is mail configuration stored?

Mail configuration is defined through environment variables in `.env` and Laravel's mail configuration in `config/mail.php`.

### 5. How do you send an email asynchronously?

Use Laravel's queue system:

```php
Mail::to($email)
    ->queue(new OrderConfirmation($order));
```

### 6. Why use queued mail?

Queued mail prevents email processing from blocking the current request and is useful for slow or high-volume email operations.

### 7. What is the difference between Mail and Notification?

**Mail** is focused on sending emails through Mailables, while **Notifications** provide a broader system for delivering notifications through multiple channels.

### 8. How can you pass data to a Mailable?

Pass the data through the Mailable's constructor:

```php
new OrderConfirmation($order)
```

Then access it inside the Mailable and its Blade view.
