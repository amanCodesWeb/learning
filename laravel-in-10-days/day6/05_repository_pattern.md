# Repository Pattern

## Overview

The **Repository Pattern** is used to separate **data-access logic** from business logic.
A Repository acts as a layer between the application and the database/model.

## Basic Flow

    Controller
        ↓
    Service
        ↓
    Repository
        ↓
    Model
        ↓
    Database

## Why Use a Repository?

Without a Repository, a Service may directly contain many database queries:

    class UserService
    {
        public function getActiveUsers()
        {
            return User::where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        public function findUser(int $id)
        {
            return User::find($id);
        }
    }

The Service now contains both:

    Business Logic
          +
    Data-Access Logic

With a Repository, these responsibilities are separated.

## Basic Repository

    class UserRepository
    {
        public function findById(int $id)
        {
            return User::find($id);
        }

        public function getActiveUsers()
        {
            return User::where('status', 'active')->get();
        }

        public function create(array $data)
        {
            return User::create($data);
        }
    }

The Repository handles the database-related operations.

## Using Repository in a Service

    class UserService
    {
        public function __construct(
            private UserRepository $repository
        ) {}

        public function getUser(int $id)
        {
            return $this->repository->findById($id);
        }
    }

Now:

    UserService
    → Business logic

    UserRepository
    → Data-access logic

## Example with Business Logic

    class OrderService
    {
        public function __construct(
            private OrderRepository $repository
        ) {}

        public function createOrder(array $data)
        {
            // Business logic
            $data['total'] = $data['price'] * $data['quantity'];

            return $this->repository->create($data);
        }
    }

Repository:

    class OrderRepository
    {
        public function create(array $data)
        {
            return Order::create($data);
        }
    }

The Service decides **what should happen**.
The Repository handles **how data is stored/retrieved**.

## Repository Interface

For larger applications, an interface can be used.

    interface UserRepositoryInterface
    {
        public function findById(int $id);
        public function create(array $data);
        public function getActiveUsers();
    }

Implementation:

    class UserRepository implements UserRepositoryInterface
    {
        public function findById(int $id)
        {
            return User::find($id);
        }

        public function create(array $data)
        {
            return User::create($data);
        }

        public function getActiveUsers()
        {
            return User::where('status', 'active')->get();
        }
    }

## Binding the Interface

Register the implementation with Laravel's Service Container:

    $this->app->bind(
        UserRepositoryInterface::class,
        UserRepository::class
    );

Now Laravel knows:

    UserRepositoryInterface
            ↓
    UserRepository

## Injecting the Interface

The Service depends on the interface:

    class UserService
    {
        public function __construct(
            private UserRepositoryInterface $repository
        ) {}

        public function getUser(int $id)
        {
            return $this->repository->findById($id);
        }
    }

Laravel resolves the correct implementation through the Service Container.

## Why Use an Interface?

Instead of tightly coupling the Service to:

    UserRepository

we depend on:

    UserRepositoryInterface

This allows the implementation to be changed.

For example:

    UserRepository
        ↓
    MySQL / Eloquent

Later:

    ApiUserRepository
        ↓
    External API

The Service can continue depending on the same interface.

## Repository and Service Responsibilities

    Service
    ├── Business rules
    ├── Calculations
    ├── Workflows
    └── Coordinates operations

    Repository
    ├── Query database
    ├── Find records
    ├── Create records
    ├── Update records
    └── Delete records

## Repository vs Model

A Laravel Model already provides database functionality through Eloquent.

For example:

    User::find($id);
    User::where('status', 'active')->get();

Because of this, a Repository is **not always necessary**.

For simple applications:

    Controller
        ↓
    Model
        ↓
    Database

may be perfectly fine.

For applications with complex data-access logic or a specific architectural requirement:

    Controller
        ↓
    Service
        ↓
    Repository
        ↓
    Model
        ↓
    Database

can provide additional separation.

## Important

Repository Pattern is **not required by Laravel**.

It is an architectural pattern.

Laravel already provides Eloquent as a powerful data-access layer, so adding repositories should have a clear purpose rather than being done automatically for every model.

## Common Structure

    app/
    ├── Repositories/
    │   ├── UserRepository.php
    │   └── OrderRepository.php
    │
    └── Services/
        ├── UserService.php
        └── OrderService.php

If interfaces are used:

    app/
    └── Repositories/
        ├── Contracts/
        │   ├── UserRepositoryInterface.php
        │   └── OrderRepositoryInterface.php
        │
        ├── UserRepository.php
        └── OrderRepository.php

## Complete Flow

    HTTP Request
         ↓
    Controller
         ↓
    Service
         ↓
    Repository Interface
         ↓
    Repository
         ↓
    Eloquent Model
         ↓
    Database

## Interview Questions

### Q1. What is the Repository Pattern?

The Repository Pattern separates data-access logic from business logic by providing a dedicated layer for retrieving and storing data.

### Q2. What is the difference between Service and Repository?

Service handles **business logic**.

Repository handles **data-access logic**.

### Q3. Is Repository required in Laravel?

No. Laravel's Eloquent already provides database access. Repositories are an optional architectural pattern.

### Q4. Why use a Repository Interface?

It allows the application to depend on an abstraction rather than a specific implementation, reducing coupling.

### Q5. When should you use a Repository?

Use one when it provides meaningful separation, such as complex data-access logic, multiple data sources, or an architecture that benefits from abstraction.

## Interview Point

> A Repository separates data-access logic from business logic and provides a consistent interface for retrieving and storing application data.