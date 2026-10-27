# Service Container

## Overview

The **Service Container** is Laravel's system for managing class dependencies and performing **Dependency Injection**.

It automatically creates and provides the objects that a class needs.

## Basic Example

    class PaymentService
    {
        public function pay()
        {
            return "Payment processed";
        }
    }

    class OrderService
    {
        public function __construct(
            private PaymentService $payment
        ) {}
    }

When Laravel creates `OrderService`, it sees that it needs `PaymentService`.

The container resolves the dependency automatically:

    OrderService
          ↓
    needs PaymentService
          ↓
    Service Container
          ↓
    creates PaymentService
          ↓
    injects it into OrderService

## Resolving a Class Manually

Laravel can resolve a class from the container using `app()`:

    $service = app(OrderService::class);

Or:

    $service = resolve(OrderService::class);

Both ask Laravel's container to resolve the class and its dependencies.

## Binding a Class

You can explicitly tell Laravel which implementation should be used.

    $this->app->bind(
        PaymentInterface::class,
        StripePaymentService::class
    );

Now whenever Laravel needs:

    PaymentInterface

it will provide:

    StripePaymentService

## Interface Example

    interface PaymentInterface
    {
        public function pay();
    }

    class StripePaymentService implements PaymentInterface
    {
        public function pay()
        {
            return "Paid using Stripe";
        }
    }

Binding:

    $this->app->bind(
        PaymentInterface::class,
        StripePaymentService::class
    );

Service:

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

Laravel sees:

    OrderService
          ↓
    needs PaymentInterface
          ↓
    Container checks binding
          ↓
    StripePaymentService
          ↓
    Injects it into OrderService

## Why Use the Service Container?

- Handles dependency resolution
- Supports Dependency Injection
- Reduces tight coupling
- Allows interfaces to have different implementations
- Makes testing easier
- Centralizes dependency configuration

## Service Container and Dependency Injection

They are closely related but not the same thing.

    Dependency Injection
    → The technique of providing dependencies.

    Service Container
    → Laravel's system that resolves and provides those dependencies.

Example:

    class UserService
    {
        public function __construct(
            private UserRepository $repository
        ) {}
    }

The constructor uses **Dependency Injection**.
Laravel's **Service Container** creates/resolves `UserRepository` and injects it.

## Binding vs Resolving

### Binding

Tell Laravel what implementation to use:

    $this->app->bind(
        PaymentInterface::class,
        StripePaymentService::class
    );

### Resolving

Ask Laravel to create the object:
    $payment = app(PaymentInterface::class);

The container checks the binding and returns:
    StripePaymentService

## Singleton Binding

`singleton()` creates one shared instance from the container.

    $this->app->singleton(
        PaymentService::class,
        function ($app) {
            return new PaymentService();
        }
    );

After it is resolved, Laravel reuses the same instance during the application's lifecycle.

## bind() vs singleton()

    bind()
    → Creates/resolves a new instance when needed.

    singleton()
    → Reuses the same resolved instance.

## Interview Questions

### Q1. What is Laravel's Service Container?

The Service Container is Laravel's system for managing and resolving class dependencies.

### Q2. How does the Service Container support Dependency Injection?

It automatically resolves dependencies and injects them into constructors or methods.

### Q3. Why bind an interface to a class?

It allows the application to depend on an abstraction instead of a specific implementation.

### Q4. What is the difference between `bind()` and `singleton()`?

`bind()` provides a normal container binding, while `singleton()` ensures the same resolved instance is reused.

## Interview Point

> Laravel's Service Container manages class dependencies and automatically resolves them when they are required.