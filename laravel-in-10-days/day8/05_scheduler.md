# Scheduler

## Overview

Laravel **Scheduler** allows you to define tasks that should run automatically at specific times or intervals.

Instead of manually running commands, Laravel can execute them automatically.

Examples:

- Run a task every minute
- Send daily reports
- Delete expired records
- Process pending orders
- Clean old files
- Sync data with third-party APIs

---

## Why Use Scheduler?

Without a scheduler, you may need to manually run commands:

```php
php artisan orders:process
```

With the scheduler, Laravel can run the command automatically:

Every minute
     ↓
Laravel Scheduler
     ↓
Process pending orders

Benefits:
- Automates repetitive tasks
- Centralizes scheduled tasks
- Reduces manual work
- Supports different frequencies
- Works with Artisan commands, jobs, and closures

---

## Creating a Scheduled Task

Laravel scheduling is typically defined in the application's console scheduling configuration.

A scheduled task can call an Artisan command:

```php
Schedule::command('orders:process')->daily();
```

For example:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('orders:process')
    ->daily();
```

This means the `orders:process` command should run once per day.

---

## Common Scheduling Frequencies

Laravel provides several scheduling methods.

```php
Schedule::command('task')->everyMinute();
Schedule::command('task')->everyFiveMinutes();
Schedule::command('task')->everyTenMinutes();
Schedule::command('task')->everyFifteenMinutes();
Schedule::command('task')->hourly();
Schedule::command('task')->daily();
Schedule::command('task')->weekly();
Schedule::command('task')->monthly();
```

These methods define **when** the task should run.

---

## Running a Task at a Specific Time

You can schedule a task for a specific time.

```php
Schedule::command('reports:generate')
    ->dailyAt('09:00');
```

This runs the command every day at 9:00 AM.

Another example:

```php
Schedule::command('orders:cleanup')
    ->dailyAt('23:00');
```

This runs every day at 11:00 PM.

---

## Scheduling a Job

The Scheduler can dispatch a Job.

```php
Schedule::job(new ProcessPendingOrders)
    ->hourly();
```

Flow:

Scheduler
    ↓
Job
    ↓
Queue
    ↓
Queue Worker
    ↓
Job executes

This is useful when the scheduled task performs heavy work.

---

## Scheduling a Closure

You can also schedule a closure directly.

```php
Schedule::call(function () {
    // Task logic
})->daily();
```

However, for larger tasks, using an Artisan command or Job is usually easier to maintain.

---

## Scheduling Artisan Commands

You can schedule custom Artisan commands.

First create a command:

```php
php artisan make:command ProcessOrders
```

Then schedule it:

```php
Schedule::command('orders:process')
    ->everyFiveMinutes();
```

Flow:

Scheduler
    ↓
Artisan Command
    ↓
Application Logic

---

## Example: E-Commerce Order Processing

Suppose your application has pending orders that need to be processed every 10 minutes.

```php
Schedule::command('orders:process')
    ->everyTenMinutes();
```

Flow:

Every 10 Minutes
       ↓
Scheduler
       ↓
orders:process
       ↓
Find Pending Orders
       ↓
Process Orders

---

## Scheduler + Job + Queue

For a large task, it is better to let the scheduler dispatch a queued Job.

```php
Schedule::job(new SyncMarketplaceOrders)
    ->everyTenMinutes();
```

Flow:

Scheduler
    ↓
Dispatch Job
    ↓
Queue
    ↓
Queue Worker
    ↓
Sync Marketplace Orders

This prevents a long-running task from blocking the scheduler.

---

## Preventing Overlapping Tasks

Sometimes a scheduled task may still be running when the next scheduled execution starts.

For example:

10:00 → Task starts
10:05 → Task starts again

If the first task takes more than five minutes, two instances may run at the same time.

Laravel provides `withoutOverlapping()`:

```php
Schedule::command('orders:process')
    ->everyFiveMinutes()
    ->withoutOverlapping();
```

Now Laravel prevents another instance from starting while the previous one is still running.

---

## Running Tasks on One Server

In a multi-server application, the same scheduled task could potentially run on multiple servers.

Laravel provides `onOneServer()`:

```php
Schedule::command('reports:generate')
    ->daily()
    ->onOneServer();
```

This is useful when your application is running on multiple servers.

---

## Scheduling in Maintenance

Scheduled tasks can also be configured to run or skip based on application conditions.

For example:

```php
Schedule::command('orders:process')
    ->everyMinute()
    ->when(fn () => app()->environment('production'));
```

This allows the task to run only when the condition is true.

---

## Scheduler vs Queue

| Scheduler | Queue |
|---|---|
| Controls **when** something runs | Controls **how/when asynchronously** a job runs |
| Time-based | Background processing |
| Runs commands, jobs, closures | Runs queued jobs/listeners |
| Example: every hour | Example: process email in background |

Simple way to remember:

Scheduler = When should it run?
Queue = Run this work in the background.

They can work together:

Scheduler
    ↓
Dispatch Job
    ↓
Queue
    ↓
Worker
    ↓
Job executes

---

## Scheduler vs Event

| Scheduler | Event |
|---|---|
| Runs based on time | Runs because something happened |
| Time-driven | Event-driven |
| Example: every day at 9 AM | Example: OrderCreated |
| Used for recurring tasks | Used for application events |

Example:

Scheduler:
Every day at 9 AM
        ↓
Generate Sales Report

Event:
OrderCreated
        ↓
Send Confirmation

---

## Important: Scheduler Needs a Trigger

Defining a scheduled task does not mean Laravel automatically executes it by itself.
The scheduler needs a mechanism to trigger it.
In production, the scheduler is commonly triggered every minute:

```php
php artisan schedule:run
```

A system cron can execute this command every minute.

Conceptually:

System Cron
    ↓
php artisan schedule:run
    ↓
Laravel checks scheduled tasks
    ↓
Due task runs

---

## Running Scheduler Locally

You can use Laravel's scheduler worker during development:

```php
php artisan schedule:work
```

It continuously runs the scheduler and is useful while developing or testing scheduled tasks.

---

## Example: Daily Cleanup

Suppose expired sessions should be removed every night.

```php
Schedule::command('sessions:cleanup')
    ->dailyAt('02:00');
```

Flow:

Every Day at 2 AM
       ↓
Scheduler
       ↓
sessions:cleanup
       ↓
Remove Expired Sessions

---

## Example: Marketplace Sync

For an e-commerce application:

```php
Schedule::job(new SyncMarketplaceOrders)
    ->everyTenMinutes();
```

Flow:

Every 10 Minutes
       ↓
Scheduler
       ↓
SyncMarketplaceOrders Job
       ↓
Queue
       ↓
Queue Worker
       ↓
Marketplace API

This is useful for periodically synchronizing orders, products, inventory, or other data with external platforms.

---

## Key Points

- Scheduler automates time-based tasks.
- Tasks can run every minute, hourly, daily, weekly, etc.
- Scheduler can execute Artisan commands.
- Scheduler can dispatch Jobs.
- Scheduler can execute closures.
- `withoutOverlapping()` prevents duplicate concurrent executions.
- `onOneServer()` helps prevent duplicate execution in multi-server environments.
- `schedule:run` checks which tasks are due.
- `schedule:work` continuously runs the scheduler during development.
- Scheduler and Queue can work together.

---

## Interview Questions

### 1. What is Laravel Scheduler?

Laravel Scheduler is a system for defining and automatically running recurring tasks at specific times or intervals.

### 2. How is Scheduler different from Queue?

Scheduler determines **when** a task should run, while Queue handles background/asynchronous processing.

### 3. Can Scheduler dispatch Jobs?

Yes. A scheduled task can dispatch a Job to the queue.

### 4. What does `withoutOverlapping()` do?

It prevents a scheduled task from starting again while its previous execution is still running.

### 5. What is `schedule:run`?

It checks the application's scheduled tasks and executes the tasks that are due to run.

### 6. What is `schedule:work`?

It continuously runs the scheduler and is useful during local development.

### 7. Give a real-world Scheduler example.

For an e-commerce application:

```php
Schedule::job(new SyncMarketplaceOrders)
    ->everyTenMinutes();
```

This periodically dispatches a job that synchronizes marketplace orders in the background.