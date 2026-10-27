# DTO — Data Transfer Object

## Overview

**DTO (Data Transfer Object)** is an object used to transfer structured data between different parts of an application.

Instead of passing large or unstructured arrays between layers, a DTO defines exactly what data should be transferred.

## Without DTO

A Service may receive an array:

    $data = [
        'name' => 'Aman',
        'email' => 'aman@example.com',
        'phone' => '9876543210',
    ];

    $userService->create($data);

The problem is that the structure of `$data` is not strongly defined.

Another developer could accidentally pass:

    $data = [
        'username' => 'Aman',
        'email_address' => 'aman@example.com',
    ];

The Service may not know what fields are expected.

## With DTO

Create a dedicated DTO:

    class CreateUserDTO
    {
        public function __construct(
            public string $name,
            public string $email,
            public string $phone
        ) {}
    }

Now create the DTO:

    $dto = new CreateUserDTO(
        $request->name,
        $request->email,
        $request->phone
    );

Pass it to the Service:
    $userService->create($dto);

The Service now clearly expects:
    CreateUserDTO

## DTO Example

    class CreateUserDTO
    {
        public function __construct(
            public string $name,
            public string $email,
            public string $phone
        ) {}
    }

Service:

    class UserService
    {
        public function create(CreateUserDTO $dto)
        {
            return User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'phone' => $dto->phone,
            ]);
        }
    }

Usage:

    $dto = new CreateUserDTO(
        'Aman',
        'aman@example.com',
        '9876543210'
    );

    $userService->create($dto);

## DTO Flow

    Request
       ↓
    Validate Data
       ↓
    Create DTO
       ↓
    Service
       ↓
    Repository / Model

## Why Use DTOs?
- Provides a clear data structure
- Improves type safety
- Makes dependencies between layers clearer
- Reduces large/unstructured arrays
- Makes code easier to understand
- Helps prevent accidental data changes
- Useful when data moves between multiple layers

## DTO with a Static Factory Method

Instead of creating the DTO manually:

    $dto = new CreateUserDTO(
        $request->name,
        $request->email,
        $request->phone
    );

The DTO can provide a factory method:

    class CreateUserDTO
    {
        public function __construct(
            public string $name,
            public string $email,
            public string $phone
        ) {}

        public static function fromRequest(Request $request): self
        {
            return new self(
                $request->name,
                $request->email,
                $request->phone
            );
        }
    }

Now:
    $dto = CreateUserDTO::fromRequest($request);

This keeps DTO creation logic in one place.

## DTO with Validation

Validation should normally happen before creating the DTO.

Example:

    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
    ]);

Then:

    $dto = new CreateUserDTO(
        $validated['name'],
        $validated['email'],
        $validated['phone']
    );

This gives a clean flow:

    Request
       ↓
    Validation
       ↓
    DTO
       ↓
    Service

## DTO vs Array

### Array

    $data = [
        'name' => 'Aman',
        'email' => 'aman@example.com',
    ];

Flexible, but the expected structure is not enforced by the type system.

### DTO

    class CreateUserDTO
    {
        public function __construct(
            public string $name,
            public string $email
        ) {}
    }

The structure and expected types are explicitly defined.

## DTO vs Model

A DTO and Model have different responsibilities.

    DTO
    → Transfers data between application layers.

    Model
    → Represents application/database data and interacts with persistence.

Example:

    CreateUserDTO
    → Data received when creating a user.

    User
    → Represents the user stored in the database.

A DTO does not need to represent a database table.

## DTO vs Request

A Request represents the incoming HTTP request.

    Request
    → HTTP layer

A DTO represents the structured data that the application wants to work with.

    Request
        ↓
    Validate
        ↓
    DTO
        ↓
    Service

This helps keep HTTP-specific objects out of deeper application layers.

## DTO + Service + Repository

A common architecture:

    Controller
        ↓
    DTO
        ↓
    Service
        ↓
    Repository
        ↓
    Model
        ↓
    Database

Example:

    class UserController
    {
        public function __construct(
            private UserService $service
        ) {}

        public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
            ]);

            $dto = new CreateUserDTO(
                $validated['name'],
                $validated['email']
            );

            return $this->service->create($dto);
        }
    }

Service:

    class UserService
    {
        public function __construct(
            private UserRepository $repository
        ) {}

        public function create(CreateUserDTO $dto)
        {
            // Business logic

            return $this->repository->create($dto);
        }
    }

Repository:

    class UserRepository
    {
        public function create(CreateUserDTO $dto)
        {
            return User::create([
                'name' => $dto->name,
                'email' => $dto->email,
            ]);
        }
    }

## Important

DTOs are **not required by Laravel**.

Laravel does not force you to use DTOs.

They are an architectural pattern that becomes useful when an application has complex data flows or multiple application layers.

For simple CRUD operations, using validated arrays may be completely reasonable.

## Common DTO Structure

A project might organize DTOs like:

    app/
    └── DTOs/
        ├── CreateUserDTO.php
        ├── UpdateUserDTO.php
        ├── CreateOrderDTO.php
        └── CreatePaymentDTO.php

The folder name is a convention and can vary between projects.

## Interview Questions

### Q1. What is a DTO?

A DTO (Data Transfer Object) is an object used to transfer structured data between different parts or layers of an application.

### Q2. Why use a DTO instead of an array?

A DTO provides a clearly defined structure and types, making data transfer more predictable and maintainable.

### Q3. Is DTO a Laravel feature?

No. DTO is an architectural pattern. Laravel does not require DTOs.

### Q4. DTO vs Model?

A DTO transfers data between layers, while a Model represents application/database data and handles persistence-related operations.

### Q5. DTO vs Request?

A Request belongs to the HTTP layer and represents the incoming request. A DTO represents structured application data and can be passed to deeper application layers.

### Q6. Should validation happen inside the DTO?

Usually, request validation is handled before creating the DTO. The DTO's main responsibility is representing and transferring structured data.

## Interview Point

> A DTO is a structured object used to transfer data between application layers while clearly defining the expected data and types.