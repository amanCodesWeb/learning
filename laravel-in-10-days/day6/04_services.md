# Services

## Overview

A **Service** is a class used to keep business logic separate from Controllers and Models.

Laravel does not require you to create Service classes. They are an **architectural pattern/convention** used to organize application logic.

## Why Use Services?

Without a Service, a Controller can become too large:

    Controller
        ↓
    Validate request
        ↓
    Calculate prices
        ↓
    Process payment
        ↓
    Create order
        ↓
    Send notification
        ↓
    Update inventory

Instead, move the business logic into a Service:

    Controller
        ↓
    OrderService
        ↓
    Business Logic

The Controller handles the HTTP request, while the Service handles the actual business operation.

## Basic Service Example

    class OrderService
    {
        public function createOrder(array $data)
        {
            // Validate business rules
            // Calculate totals
            // Create order
            // Process payment
            // Update inventory

            return $order;
        }
    }

Controller:

    class OrderController
    {
        public function __construct(
            private OrderService $orderService
        ) {}

        public function store(Request $request)
        {
            return $this->orderService->createOrder(
                $request->all()
            );
        }
    }

## What Should a Service Contain?

A Service should generally contain **business logic**.

Examples:

    OrderService
    → Create orders
    → Calculate order totals
    → Process order workflow

    PaymentService
    → Process payments
    → Refund payments
    → Verify transactions

    ShippingService
    → Create shipments
    → Calculate shipping
    → Track shipments

    UserService
    → Register users
    → Update user-related business logic

## Service vs Controller

### Controller

Responsible mainly for handling the HTTP layer:

    Request
      ↓
    Controller
      ↓
    Service
      ↓
    Response

Example:

    public function store(Request $request)
    {
        return $this->orderService->createOrder(
            $request->all()
        );
    }

### Service

Responsible for business logic:

    public function createOrder(array $data)
    {
        // Business logic
    }

## Service vs Model

A Model represents and interacts with application data/entities.
A Service handles business workflows involving one or more models/services.

Example:

    Order Model
    → Represents an order.

    OrderService
    → Handles the process of creating an order.

Example:

    class OrderService
    {
        public function createOrder(array $data)
        {
            $total = $this->calculateTotal($data);

            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'total' => $total,
            ]);

            return $order;
        }

        private function calculateTotal(array $data)
        {
            // Business calculation
        }
    }

## Service with Dependency Injection

Services can depend on other services.

    class OrderService
    {
        public function __construct(
            private PaymentService $payment,
            private ShippingService $shipping
        ) {}

        public function createOrder(array $data)
        {
            // Create order
            $this->payment->pay();
            $this->shipping->createShipment();
        }
    }

Laravel's Service Container resolves these dependencies automatically.

## Service + Repository

A common architecture is:

    Controller
        ↓
    Service
        ↓
    Repository
        ↓
    Model
        ↓
    Database

Example:

    class UserService
    {
        public function __construct(
            private UserRepository $repository
        ) {}

        public function createUser(array $data)
        {
            // Business logic

            return $this->repository->create($data);
        }
    }

Here:

    UserService
    → Business logic

    UserRepository
    → Data-access logic

## Important

Do not create a Service class just to move every single line of code out of a Controller.
The purpose is to separate **meaningful business logic** and make responsibilities clearer.
For simple CRUD operations, a Controller can sometimes work directly with a Model without requiring a Service.

## Common Structure

A project might organize services like:

    app/
    └── Services/
        ├── OrderService.php
        ├── PaymentService.php
        ├── ShippingService.php
        └── UserService.php

This is a convention, not a Laravel requirement.

## Service Flow

    HTTP Request
         ↓
    Controller
         ↓
    Service
         ↓
    Repository / Model / External API
         ↓
    Result
         ↓
    Controller
         ↓
    HTTP Response

## Interview Questions

### Q1. What is a Service class?

A Service class contains business logic that is better kept outside Controllers and Models.

### Q2. Is Service a Laravel built-in feature?

No. Service classes are an architectural convention/pattern. Laravel's Service Container and Dependency Injection make them easy to use.

### Q3. What should a Service contain?

Business logic and workflows, such as order processing, payment processing, shipping workflows, or other application-specific operations.

### Q4. What is the difference between a Controller and a Service?

A Controller mainly handles the HTTP request/response layer, while a Service handles business logic.

### Q5. Should every Controller have a Service?

No. Services are useful when business logic becomes complex or needs to be reused. Simple operations do not always require a Service.

## Interview Point

> A Service class is used to organize business logic separately from Controllers and Models, making the application easier to maintain, test, and reuse.