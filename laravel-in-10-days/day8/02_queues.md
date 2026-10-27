# Queues

## Overview

A **Queue** allows Laravel to process time-consuming tasks in the background instead of making the user wait.

For example:

User places order
       ↓
Create order
       ↓
Dispatch Job
       ↓
Return response
       ↓
Queue Worker processes Job

The user does not need to wait for the background task to finish.

## Why Use Queues?

Without a queue:

Request
   ↓
Send email
   ↓
Call shipping API
   ↓
Generate report
   ↓
Response

The user must wait for all operations.

With a queue:

Request
   ↓
Dispatch Job
   ↓
Response

Then in the background:

Queue
   ↓
Queue Worker
   ↓
Process Job

This makes the application more responsive.

## Job vs Queue

A **Job** defines the task.
A **Queue** holds tasks waiting to be processed.

Job
→ What should be done?

Queue
→ Where does the task wait before being processed?

Example:

SendOrderConfirmation Job
          ↓
        Queue
          ↓
     Queue Worker
          ↓
      Email sent

## Queue Driver

Laravel supports different queue backends, such as:

Database
Redis
Amazon SQS

The queue driver determines where Laravel stores pending jobs.

For example:

```env
QUEUE_CONNECTION=database
```

With the database driver, queued jobs are stored in a database table.

## Database Queue

For a database queue, Laravel uses a `jobs` table to store pending jobs.
After creating the required migration, run:

```php
php artisan migrate
```

The basic flow becomes:

Job dispatched
      ↓
jobs table
      ↓
Queue Worker
      ↓
Job processed
      ↓
Job removed

## Dispatching a Job

Suppose we have a Job:

```php
class SendOrderConfirmation implements ShouldQueue
{
    public function __construct(
        public Order $order
    ) {}

    public function handle()
    {
        // Send email
    }
}
```

Dispatch it:

```php
SendOrderConfirmation::dispatch($order);
```

The Job is placed into the configured queue.

## Queue Worker

A **Queue Worker** processes jobs from the queue.

Start a worker with:

```php
php artisan queue:work
```

The worker continuously checks for pending jobs.

Queue
  ↓
Queue Worker
  ↓
Get Job
  ↓
Execute handle()
  ↓
Job completed

## Delayed Jobs

A Job can be delayed before it becomes available for processing.

```php
SendOrderConfirmation::dispatch($order)
    ->delay(now()->addMinutes(5));
```

The Job becomes available after the specified delay.

## Failed Jobs

A Job can fail because of:

API failure
Database error
Network problem
Invalid data
Third-party service unavailable

Laravel can store information about failed jobs.

View failed jobs:

```php
php artisan queue:failed
```

Retry a failed job:

```php
php artisan queue:retry all
```

## Job Attempts

A Job can be configured with a maximum number of attempts.

```php
class SendOrderConfirmation implements ShouldQueue
{
    public $tries = 3;

    public function handle()
    {
        // Task logic
    }
}
```

If the Job fails, Laravel can retry it according to the configured attempts.

## Queue Worker vs Job

These are different concepts:

Job
→ Defines the task.

Queue
→ Stores pending tasks.

Worker
→ Takes tasks from the queue and executes them.

Think of it like:

Job      = Task
Queue    = Waiting line
Worker   = Person processing the tasks

## Queue Flow

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
      Finished

## When Should You Use Queues?

Queues are useful for tasks that:
- Take significant time
- Do not need to finish before the HTTP response
- Can safely run in the background
- May involve external services

Common examples:

Send emails
Process images
Generate reports
Call external APIs
Process large datasets
Send notifications
Process orders

## Important Concept

Queues are mainly about **asynchronous/background processing**.

Instead of:

Request → Do everything → Response

we can do:

Request → Dispatch Job → Response
                 ↓
          Queue Worker
                 ↓
          Process Job

## Key Points

- A Queue stores tasks waiting to be processed.
- Jobs are commonly placed onto queues.
- A Queue Worker processes queued Jobs.
- `queue:work` starts the worker.
- Queue drivers determine where queued Jobs are stored.
- Jobs can be delayed.
- Failed Jobs can be stored and retried.
- Queues are useful for slow or background operations.

## Interview Questions

### What is a Queue in Laravel?

A Queue is a system that allows Laravel to defer time-consuming tasks and process them asynchronously using queue workers.

### What is a Queue Worker?

A Queue Worker is a process that continuously retrieves pending Jobs from a Queue and executes them.

### What is the difference between a Job and a Queue?

A Job defines **what task should be performed**, while a Queue stores Jobs that are waiting to be processed.

### What does `queue:work` do?

```php
php artisan queue:work
```

It starts a worker that processes pending Jobs from the configured queue.