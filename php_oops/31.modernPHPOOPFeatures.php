<!--

=========================================================
MODERN PHP OOP FEATURES
=========================================================

Modern PHP (8.0+) introduced several features that make code safer,
cleaner, and easier to maintain.

=========================================================
1. Enums (PHP 8.1)
=========================================================

An Enum (Enumeration) represents a fixed set of possible values.

Instead of using strings like:
"pending"
"paid"
"cancelled"

Use an Enum.

Example

enum OrderStatus
{
    case Pending;
    case Paid;
    case Cancelled;
}

Usage
$status = OrderStatus::Paid;

Benefits
✔ Type Safe
✔ Prevents invalid values
✔ Better readability

Use Cases
• Order Status
• User Role
• Payment Status
• Shipping Status


=========================================================
2. Readonly Properties (PHP 8.1)
=========================================================

A readonly property can only be assigned once.
Usually inside the constructor.

Example

class User
{
    public function __construct(
        public readonly string $name
    ) {}
}

$user = new User("doe");
$user->name = "John"; // Error

Benefits
✔ Immutable data
✔ Prevent accidental modification


=========================================================
3. Readonly Classes (PHP 8.2)
=========================================================

A readonly class makes every property readonly.

Example

readonly class User
{
    public function __construct(
        public string $name,
        public int $age
    ) {}
}

All properties become readonly automatically.

Benefits
✔ Less code
✔ Immutable objects


=========================================================
31.4 Attributes (PHP 8.0)
=========================================================

Attributes add metadata to classes, methods, properties or parameters.

Syntax
#[Attribute]

Example

#[Route('/users')]
class UserController
{
}

Use Cases
• Routing
• Validation
• Dependency Injection
• ORM Mapping

Benefits
✔ Native PHP feature
✔ Replaces PHPDoc annotations


=========================================================
5. Named Arguments (PHP 8.0)
=========================================================

Allows passing arguments by parameter name.
Instead of
createUser("john", 22, true);

Write

createUser(
    name: "john",
    age: 22,
    active: true
);

Benefits
✔ More readable
✔ Skip optional parameters
✔ Order becomes less important


=========================================================
6. Nullsafe Operator (PHP 8.0)
=========================================================

Safely access properties or methods on objects that may be null.

Instead of

if ($user != null)
{
    $city = $user->address->city;
}

Write
$city = $user?->address?->city;

If any object is null,

Result
null

instead of
Fatal Error

Benefits
✔ Cleaner code
✔ Less nested if statements


=========================================================
Interview Questions
=========================================================

Q. What is an Enum?
A type that represents a fixed set of predefined values.

-------------------------------------

Q. Why use Enums instead of strings?
They provide type safety and prevent invalid values.

-------------------------------------

Q. What is a readonly property?
A property that can only be assigned once.

-------------------------------------

Q. What is a readonly class?
A class where every property is automatically readonly.

-------------------------------------

Q. What are Attributes?
Native PHP metadata that can be attached to classes, methods, properties, or parameters.

-------------------------------------

Q. What are Named Arguments?
Arguments passed using parameter names instead of position.

-------------------------------------

Q. What does the Nullsafe Operator do?
Safely accesses properties or methods on nullable objects.

=========================================================
Memory Trick
=========================================================

Enum
→ Fixed Choices

Readonly
→ Cannot Change

Attributes
→ Metadata

Named Arguments
→ Name Instead of Position

Nullsafe
→ Safe Navigation

=========================================================
