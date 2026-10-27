# 9.7 Laravel Exceptions & Logging

## Definition

**Exceptions** handle errors that occur during application execution, while **logging** records information about errors and application behavior for debugging and monitoring.

## 1. Exception Handling

Laravel handles exceptions automatically and provides ways to throw, catch, and customize them.

    // Throw an exception
    throw new Exception('Something went wrong');

    // Handle an exception
    try {
        // Code that may fail
    } catch (Exception $e) {
        // Handle the error
    }

## 2. Common HTTP Exceptions

    abort(404); // Not Found
    abort(403); // Forbidden
    abort(500); // Internal Server Error

Use `abort_if()` or `abort_unless()` for conditional HTTP errors.

    abort_if(!$user, 404);

## 3. Validation Exceptions

Laravel automatically throws a validation exception when validation fails using `$request->validate()`.

    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

Laravel returns validation errors according to the request type, such as redirecting web requests or returning JSON errors for API requests.

## 4. Custom Exceptions

Create a custom exception:

    php artisan make:exception OrderNotAvailableException

Example usage:

    throw new OrderNotAvailableException('Order is unavailable.');

Custom exceptions are useful for handling specific business errors clearly.

## 5. Logging

Laravel uses logging channels configured in `config/logging.php`. By default, logs are commonly written to `storage/logs/laravel.log`.

    use Illuminate\Support\Facades\Log;

    Log::info('Order created successfully');
    Log::warning('Payment is pending');
    Log::error('Payment processing failed');

You can include context:

    Log::error('Payment failed', [
        'order_id' => $order->id,
    ]);

## 6. Log Levels

- `emergency()` — System is unusable.
- `alert()` — Immediate action required.
- `critical()` — Critical failure.
- `error()` — Operation failed.
- `warning()` — Potential problem.
- `notice()` — Significant normal event.
- `info()` — Informational message.
- `debug()` — Detailed debugging information.

## 7. Practical Example

    try {
        // Process payment
        $paymentService->charge($order);
    } catch (Exception $e) {
        Log::error('Payment failed', [
            'order_id' => $order->id,
            'message' => $e->getMessage(),
        ]);

        throw $e;
    }

The error is logged and then rethrown so Laravel's exception handler can handle it. Avoid logging sensitive information such as passwords, API secrets, or full payment details.

## 8. Important Points

- Use exceptions for exceptional failures, not normal application flow.
- Use logging to investigate issues in development and production.
- Configure log channels and levels in `config/logging.php`.
- Keep `APP_DEBUG=false` in production.
- Never expose internal exception details to users or API clients.
- Do not silently catch exceptions without handling or reporting them.

## 9. Interview Questions

**Q1. What is the difference between exceptions and logging?**  
Exceptions represent failures; logging records information about application execution and errors.

**Q2. What is `abort(404)`?**  
It throws an HTTP exception that produces a 404 response.

**Q3. Where are Laravel logs stored?**  
Commonly in `storage/logs/laravel.log`, depending on the configured logging channel.

**Q4. Why use custom exceptions?**  
To represent specific application or business errors clearly.

**Q5. Why should `APP_DEBUG` be false in production?**  
To prevent exposing sensitive internal details to users.