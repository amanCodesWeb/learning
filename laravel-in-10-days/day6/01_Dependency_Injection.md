# Dependency Injection

## Overview

**Dependency Injection (DI)** means giving a class the objects/services it needs instead of creating them inside the class.

## Without Dependency Injection

    class OrderService
    {
        public function __construct()
        {
            $this->payment = new PaymentService();
        }
    }

Here, `OrderService` creates `PaymentService` itself.

This creates **tight coupling**.

## With Dependency Injection

    class OrderService
    {
        public function __construct(
            private PaymentService $payment
        ) {}
    }

Laravel can automatically provide `PaymentService` through the **Service Container**.

## Why Use Dependency Injection?
- Reduces tight coupling
- Makes dependencies clear
- Makes code easier to test
- Makes implementations easier to replace
- Improves maintainability

## Example

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

        public function createOrder()
        {
            return $this->payment->pay();
        }
    }

The `OrderService` depends on `PaymentService`, but it does not create it itself.

## Dependency Flow

    OrderService
          ↓
    needs PaymentService
          ↓
    Dependency is provided from outside

## Laravel Example

    class OrderController
    {
        public function __construct(
            private OrderService $orderService
        ) {}

        public function store()
        {
            return $this->orderService->createOrder();
        }
    }

Laravel resolves `OrderService` and injects it into the controller.

## Constructor Injection

The most common form of DI in Laravel is **constructor injection**.

    class UserService
    {
        public function __construct(
            private UserRepository $repository
        ) {}
    }

## Method Injection

Dependencies can also be injected into methods.

    public function store(
        Request $request,
        UserService $userService
    ) {
        return $userService->create($request->all());
    }

Laravel resolves the required dependencies automatically.

## Interview Point

> Dependency Injection is a technique where dependencies are provided to a class from outside instead of the class creating them itself.