# Service Providers

## Overview

**Service Providers** are the central place in Laravel for registering and configuring application services.

They tell Laravel:
- Which services should be registered
- Which interfaces should use which implementations
- What application services need initialization

Service Providers work closely with the **Service Container**.

## Basic Structure

Laravel Service Providers contain two important methods:

    class AppServiceProvider extends ServiceProvider
    {
        public function register()
        {
            //
        }

        public function boot()
        {
            //
        }
    }

## `register()`

The `register()` method is mainly used to register services and bindings in Laravel's Service Container.

Example:

    public function register()
    {
        $this->app->bind(
            PaymentInterface::class,
            StripePaymentService::class
        );
    }

Now Laravel knows:

    PaymentInterface
          ↓
    StripePaymentService

Whenever `PaymentInterface` is injected, Laravel can provide `StripePaymentService`.

## `boot()`

The `boot()` method runs after all services have been registered.
It is used for application initialization logic.

Example:

    public function boot()
    {
        // Initialization logic
    }

A common example is registering application-level behavior that requires other services to already be available.

## `register()` vs `boot()`

    register()
        ↓
    Register services and container bindings

    boot()
        ↓
    Initialize services/application behavior

Simple rule:

> Use `register()` to register things. Use `boot()` to use things after registration.

## Example with Interface Binding

Suppose we have:

    interface PaymentInterface
    {
        public function pay();
    }

    class StripePaymentService implements PaymentInterface
    {
        public function pay()
        {
            return "Payment processed using Stripe";
        }
    }

Register the binding:

    class AppServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $this->app->bind(
                PaymentInterface::class,
                StripePaymentService::class
            );
        }
    }

Now a class can depend on the interface:

    class OrderService
    {
        public function __construct(
            private PaymentInterface $payment
        ) {}

        public function createOrder()
        {
            return $this->payment->pay();
        }
    }

Laravel resolves:

    OrderService
          ↓
    PaymentInterface
          ↓
    Service Container
          ↓
    StripePaymentService

## Why Use Service Providers?
- Register Service Container bindings
- Configure application services
- Initialize application behavior
- Keep service configuration organized
- Connect interfaces with implementations

## Custom Service Provider

For larger applications, you can create a separate provider for a specific group of services.

Example:
    php artisan make:provider PaymentServiceProvider

This creates a Service Provider where payment-related bindings can be registered.

Example:

    class PaymentServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $this->app->bind(
                PaymentInterface::class,
                StripePaymentService::class
            );
        }

        public function boot()
        {
            //
        }
    }

This keeps payment-related configuration separate from the main application provider.

## Service Provider + Service Container

The relationship is:

    Service Provider
          ↓
    Registers binding
          ↓
    Service Container
          ↓
    Resolves dependency
          ↓
    Dependency Injection

## Important

A **Service Provider is not the same as a Service class**.

    Service
    → Usually contains business logic.

    Service Provider
    → Registers/configures services with Laravel.

For example:

    OrderService
    → Business logic for orders.

    PaymentServiceProvider
    → Registers payment-related dependencies.

## Interview Questions

### Q1. What is a Service Provider?

A Service Provider is a Laravel class used to register and configure application services and container bindings.

### Q2. What is the purpose of `register()`?

It is mainly used to register services and bindings in the Service Container.

### Q3. What is the purpose of `boot()`?

It is used for initialization logic that runs after services have been registered.

### Q4. What is the difference between a Service and a Service Provider?

A Service usually contains business logic, while a Service Provider registers/configures services with Laravel.

### Q5. How are Service Providers related to the Service Container?

Service Providers register bindings and services into the Service Container, which later resolves those dependencies.

## Interview Point03_ dervice_providers

> Service Providers are Laravel's mechanism for registering and configuring services, while the Service Container is responsible for resolving those services and their dependencies.