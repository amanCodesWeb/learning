# 08. Service Providers

## What is a Service Provider?

A **Service Provider** is a Laravel class used to **register and bootstrap application services**.

It is commonly used to:

* Register services in the Service Container
* Bind interfaces to implementations
* Register application-specific configuration
* Set up services before the application handles requests

Laravel's main application provider is:

```
app/Providers/AppServiceProvider.php
```

---

## `register()` vs `boot()`

A provider mainly has two methods:

### `register()`

Used to **register bindings and services** in the container.

```php
public function register()
{
    $this->app->bind(
        PaymentGateway::class,
        StripePaymentGateway::class
    );
}
```

Use `register()` when you are defining **how a service should be resolved**.

### `boot()`

Used to **initialize or configure services** after all providers have been registered.

```php
public function boot()
{
    // Initialize application-specific behavior
}
```

Use `boot()` when the service needs other registered services to already be available.

### Simple difference

```
register() → Register services
boot()     → Start/configure services
```

---

## Example: Payment Gateway

Suppose the application supports different payment gateways.

```php
interface PaymentGateway
{
    public function charge(float $amount);
}

class StripePaymentGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Stripe payment logic
    }
}
```

Register it in `AppServiceProvider`:

```php
public function register()
{
    $this->app->bind(
        PaymentGateway::class,
        StripePaymentGateway::class
    );
}
```

Now Laravel can inject the interface:

```php
class OrderService
{
    public function __construct(
        private PaymentGateway $paymentGateway
    ) {}

    public function pay(float $amount)
    {
        return $this->paymentGateway->charge($amount);
    }
}
```

Laravel sees:

```php
PaymentGateway
      ↓
StripePaymentGateway
```

This is useful when you may later replace Stripe with another gateway.

---

## `bind()` vs `singleton()`

### `bind()`

Creates a new instance when the service is resolved.

```php
$this->app->bind(
    PaymentGateway::class,
    StripePaymentGateway::class
);
```

### `singleton()`

Uses the same instance throughout the application lifecycle.

```php
$this->app->singleton(
    PaymentGateway::class,
    StripePaymentGateway::class
);
```

Use `singleton()` when the service should have only one instance for the current application lifecycle.

---

## Creating a Custom Provider

Laravel can generate a provider:

php artisan make:provider PaymentServiceProvider

This creates:
app/Providers/PaymentServiceProvider.php

Example:

```php
class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            PaymentGateway::class,
            StripePaymentGateway::class
        );
    }

    public function boot()
    {
        // Initialization logic
    }
}
```

---

## AppServiceProvider

`AppServiceProvider` is commonly used for **application-specific registrations and bootstrapping**.

Example:

```php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register application services
    }

    public function boot()
    {
        // Application boot logic
    }
}
```

Keep large or unrelated registrations in dedicated providers instead of putting everything into `AppServiceProvider`.

---

## Real E-Commerce Example

An application may support multiple shipping providers:

```
ShippingService
      ↓
ShippingProvider interface
      ↓
┌───────────────┬───────────────┐
ShipStation     Shippo       EasyParcel
```

The provider can register which implementation Laravel should use:

```php
$this->app->bind(
    ShippingProvider::class,
    ShipStationProvider::class
);
```

Then controllers or services can depend on:
ShippingProvider

instead of directly depending on:
ShipStationProvider

This keeps the application easier to change and test.

---

## Service Provider Flow

Application starts
      ↓
Service Providers registered
      ↓
register()
      ↓
Services / bindings available
      ↓
boot()
      ↓
Application continues

---

## Important

Do not put normal business logic inside a Service Provider.

Bad:
```php
public function boot()
{
    // Process orders
    // Calculate shipping
    // Charge customers
}
```

Better:
```php
Service Provider
      ↓
Register/configure service
      ↓
Service class
      ↓
Business logic
```

---

## Remember

* Service Providers **register and bootstrap services**.
* `register()` → register bindings/services.
* `boot()` → initialize services after registration.
* `bind()` → normal container binding.
* `singleton()` → one shared instance for the application lifecycle.
* `AppServiceProvider` is commonly used for application-specific setup.
* Keep business logic in **services/actions**, not providers.
* Providers are closely connected to Laravel's **Service Container and Dependency Injection**.