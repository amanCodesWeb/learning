# Jobs

## Overview

A **Job** represents a task that Laravel needs to perform.

Jobs are commonly used for tasks that should happen separately from the main request, such as:

- Sending emails
- Processing orders
- Calling external APIs
- Generating reports
- Sending notifications

## Why Use Jobs?

Some tasks can take time.

For example:

    User places order
           ↓
    Create order
           ↓
    Send email
           ↓
    Call shipping API
           ↓
    Update inventory
           ↓
    Response

The user may have to wait for all these operations.

A Job allows us to move a task out of the main request:

    User places order
           ↓
    Create order
           ↓
    Dispatch Job
           ↓
    Return response
           ↓
    Job processes task

## Creating a Job

Create a Job using Artisan:
    php artisan make:job SendOrderConfirmation

Laravel creates:
    app/Jobs/SendOrderConfirmation.php

Basic Job:

    class SendOrderConfirmation implements ShouldQueue
    {
        public function handle()
        {
            // Task logic
        }
    }

## Dispatching a Job

A Job can be dispatched from a controller or service:
    SendOrderConfirmation::dispatch();

Laravel places the Job into the queue when queue processing is configured.

## Passing Data to a Job

Data can be passed through the constructor:

    class SendOrderConfirmation implements ShouldQueue
    {
        public function __construct(
            public Order $order
        ) {}

        public function handle()
        {
            // Use $this->order
        }
    }

Dispatch it:

    SendOrderConfirmation::dispatch($order);

## Job Flow

    Controller / Service
           ↓
    Dispatch Job
           ↓
    Queue
           ↓
    Queue Worker
           ↓
    Job::handle()
           ↓
    Task completed

## Important Concept

A Job defines **what task should be performed**.

The Queue determines **when and where that task is processed**.

    Job
    → The task

    Queue
    → The waiting system for tasks

## Key Points

- Jobs represent tasks that can be processed by Laravel.
- Jobs are useful for time-consuming operations.
- Jobs can be dispatched using `::dispatch()`.
- `ShouldQueue` allows the Job to be processed asynchronously.
- The actual Job logic is normally placed inside `handle()`.

## Interview Question

### What is a Job in Laravel?

A Job is a class that represents a task that can be executed immediately or processed asynchronously through Laravel's queue system.