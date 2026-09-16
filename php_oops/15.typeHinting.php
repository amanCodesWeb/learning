<!--

=========================================
PHP TYPE HINTING & RETURN TYPES
=========================================

Type Hinting (Type Declarations) allows you to specify the expected data type for function/method parameters and return values.

Benefits:
- Prevents invalid data types.
- Makes code easier to read.
- Improves IDE autocomplete.
- Catches bugs early.
- Makes APIs more predictable.

-----------------------------------------
Common Type Hints
-----------------------------------------

Scalar Types:
- int
- float
- string
- bool

Other Types:
- array
- object
- callable
- iterable

Class / Interface Types:
- User
- LoggerInterface

Special Return Types:
- self
- parent
- static

Special Types:
- mixed
- void
- never

Modern PHP Types:
- Nullable (?Type)
- Union (Type1|Type2)

-----------------------------------------
Strict Types
-----------------------------------------

By default PHP performs type coercion.

Example:
function test(int $id){}

test("5"); // Converts string to int

Enable strict mode:
declare(strict_types=1);

Now:
test("5");

throws TypeError.

-->

<?php

/*
|--------------------------------------------------------------------------
| 1. Scalar Type Hinting
|--------------------------------------------------------------------------
*/

function greet(string $name): string
{
    return "Hello {$name}";
}

function add(int $a, int $b): int
{
    return $a + $b;
}


/*
|--------------------------------------------------------------------------
| 2. Array Type
|--------------------------------------------------------------------------
*/

function process(array $data): array
{
    return $data;
}


/*
|--------------------------------------------------------------------------
| 3. Object Type
|--------------------------------------------------------------------------
*/

function printObject(object $obj): object
{
    return $obj;
}


/*
|--------------------------------------------------------------------------
| 4. Class Type Hint
|--------------------------------------------------------------------------
*/

class User {}

function saveUser(User $user): User
{
    return $user;
}


/*
|--------------------------------------------------------------------------
| 5. Interface Type Hint
|--------------------------------------------------------------------------
*/

interface LoggerInterface
{
    public function log(string $message);
}

class FileLogger implements LoggerInterface
{
    public function log(string $message)
    {
        echo $message;
    }
}

function writeLog(LoggerInterface $logger)
{
    $logger->log("Saved");
}


/*
|--------------------------------------------------------------------------
| 6. Nullable Types
|--------------------------------------------------------------------------
|
| ?string is shorthand for string|null
|
*/

function getName(?string $name): ?string
{
    return $name;
}


/*
|--------------------------------------------------------------------------
| 7. Union Types (PHP 8+)
|--------------------------------------------------------------------------
*/

function setId(int|string $id): int|string
{
    return $id;
}


/*
|--------------------------------------------------------------------------
| 8. Mixed Type
|--------------------------------------------------------------------------
|
| Accepts any data type.
|
*/

function debug(mixed $value): mixed
{
    return $value;
}


/*
|--------------------------------------------------------------------------
| 9. Callable Type
|--------------------------------------------------------------------------
*/

function execute(callable $callback): void
{
    $callback();
}


/*
|--------------------------------------------------------------------------
| 10. Iterable Type
|--------------------------------------------------------------------------
|
| Accepts:
| - array
| - Traversable objects
|
*/

function printItems(iterable $items): void
{
    foreach ($items as $item) {
        echo $item;
    }
}


/*
|--------------------------------------------------------------------------
| 11. Void Return Type
|--------------------------------------------------------------------------
*/

function save(): void
{
    echo "Saved";
}


/*
|--------------------------------------------------------------------------
| 12. Never Return Type (PHP 8.1+)
|--------------------------------------------------------------------------
|
| Function never returns.
| It either:
| - throws an exception
| - exits the program
|
*/

function stop(): never
{
    throw new Exception("Stopped");
}


/*
|--------------------------------------------------------------------------
| 13. Self Return Type
|--------------------------------------------------------------------------
|
| Returns an object of the current class.
|
*/

class Employee
{
    public function create(): self
    {
        return new self();
    }
}


/*
|--------------------------------------------------------------------------
| 14. Parent Return Type
|--------------------------------------------------------------------------
|
| Returns an object of the parent class.
|
*/

class Animal {}

class Dog extends Animal
{
    public function getAnimal(): parent
    {
        return new Animal();
    }
}


/*
|--------------------------------------------------------------------------
| 15. Static Return Type (PHP 8+)
|--------------------------------------------------------------------------
|
| Returns an object of the class that called the method.
| Uses Late Static Binding.
|
*/

class Builder
{
    public function setName(string $name): static
    {
        return $this;
    }
}