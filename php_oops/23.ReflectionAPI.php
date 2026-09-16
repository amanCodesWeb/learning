<!--

=========================================================
REFLECTION API
=========================================================

Reflection allows PHP to inspect and interact with classes, methods,
properties, interfaces, traits, and objects at runtime.

In simple words:
Reflection lets PHP "look inside" a class while the program is running.

It is mainly used in:
✔ Frameworks (Laravel, Symfony)
✔ Dependency Injection Containers
✔ Testing (PHPUnit)
✔ ORM
✔ Documentation Generators
✔ Attribute/Annotation Processing

=========================================================
How Reflection Works
=========================================================

            User Class

        ┌─────────────────────┐
        │ name                │
        │ email               │
        │ login()             │
        │ logout()            │
        └─────────────────────┘
                ▲
                │
          Reflection API
                │
      "What's inside this class?"

Reflection can inspect:
✔ Class Name
✔ Namespace
✔ Methods
✔ Properties
✔ Parent Class
✔ Interfaces
✔ Constructor
✔ Visibility
✔ Attributes

-->

<?php

class User
{
    private string $name = "John";

    public function __construct()
    {
    }

    public function sayHello()
    {
        return "Hello {$this->name}";
    }
}

/*
|--------------------------------------------------------------------------
| Creating Reflection Object
|--------------------------------------------------------------------------
*/

$reflection = new ReflectionClass(User::class);


/*
|--------------------------------------------------------------------------
| Class Information
|--------------------------------------------------------------------------
*/

echo $reflection->getName();              // User
echo $reflection->getShortName();         // User
echo $reflection->getNamespaceName();     // Empty (No namespace)


/*
|--------------------------------------------------------------------------
| Methods
|--------------------------------------------------------------------------
*/

$methods = $reflection->getMethods();

foreach ($methods as $method) {
    echo $method->getName() . PHP_EOL;
}

/*
Output

__construct
sayHello

*/


/*
|--------------------------------------------------------------------------
| Properties
|--------------------------------------------------------------------------
*/

$properties = $reflection->getProperties();

foreach ($properties as $property) {
    echo $property->getName() . PHP_EOL;
}

/*
Output : name
*/


/*
|--------------------------------------------------------------------------
| Check Class Information
|--------------------------------------------------------------------------
*/

var_dump($reflection->hasMethod('sayHello'));      // true
var_dump($reflection->hasProperty('name'));        // true
var_dump($reflection->isInstantiable());           // true


/*
|--------------------------------------------------------------------------
| Create Object Dynamically
|--------------------------------------------------------------------------
|
| Reflection can create objects without using "new".
|
*/

$user = $reflection->newInstance();
echo $user->sayHello();


/*
|--------------------------------------------------------------------------
| Constructor Arguments
|--------------------------------------------------------------------------
*/

class Employee
{
    public function __construct(
        public string $name,
        public int $age
    ) {
    }
}

$reflection = new ReflectionClass(Employee::class);

$employee = $reflection->newInstanceArgs([
    "John",
    24
]);

echo $employee->name;


/*
|--------------------------------------------------------------------------
| Access Private Property
|--------------------------------------------------------------------------
|
| Reflection can bypass visibility.
|
| Useful for:
| - Testing
| - Frameworks
|
*/

$user = new User();
$reflection = new ReflectionClass(User::class);
$property = $reflection->getProperty('name');

$property->setAccessible(true);   // Deprecated in PHP 8.1+, not required in newer versions for reflection access.

echo $property->getValue($user);


/*
|--------------------------------------------------------------------------
| Common Reflection Methods
|--------------------------------------------------------------------------
|

Class Information:
getName()
getShortName()
getNamespaceName()

-----------------------------------------

Methods:
getMethods()
getMethod()
hasMethod()

-----------------------------------------

Properties:
getProperties()
getProperty()
hasProperty()

-----------------------------------------

Object Creation:
newInstance()
newInstanceArgs()

-----------------------------------------

Type Checking:
isInstantiable()
isSubclassOf()
implementsInterface()

*/


/*
|--------------------------------------------------------------------------
| Real Laravel Examples
|--------------------------------------------------------------------------
|

1. Service Container

Laravel uses Reflection to inspect constructor parameters
and automatically inject dependencies.

Example

class UserService
{
    public function __construct(
        Logger $logger,
        MailService $mail
    ) {}
}

Laravel automatically creates Logger and MailService.

-------------------------------------------------------

2. Route Model Binding

public function show(User $user)

Reflection detects the User type and Laravel automatically fetches the model.

-------------------------------------------------------

3. PHPUnit

Reflection is used to inspect or test private/protected members.

-------------------------------------------------------

4. Attributes

#[Route('/users')]
Reflection reads Attributes at runtime.

*/


/*
|--------------------------------------------------------------------------
| Advantages
|--------------------------------------------------------------------------
|

✔ Dynamic Programming
✔ Automatic Dependency Injection
✔ Framework Development
✔ Testing
✔ Documentation Generation
✔ ORM Mapping

*/


/*
|--------------------------------------------------------------------------
| Disadvantages
|--------------------------------------------------------------------------
|

✖ Slower than direct access
✖ Can break encapsulation
✖ Makes code more complex
✖ Should not be overused

*/


/*
|--------------------------------------------------------------------------
| Interview Questions
|--------------------------------------------------------------------------
|

Q. What is Reflection?
Reflection allows PHP to inspect classes, methods, properties, interfaces and objects at runtime.

-------------------------------------

Q. Why do frameworks use Reflection?
To automatically discover information about classes for Dependency Injection, Routing, ORM, Testing, and Attributes.

-------------------------------------

Q. Can Reflection access private properties?
Yes. Reflection can inspect and access private/protected members.

-------------------------------------

Q. Is Reflection fast?

No. Reflection is slower than direct access because PHP must inspect metadata at runtime.

-------------------------------------

Q. Name some real-world uses of Reflection.

• Laravel Service Container
• Route Model Binding
• PHPUnit
• Doctrine ORM
• Swagger/OpenAPI
• Attribute Processing

*/
