<!--

=========================================================
PHP MAGIC METHODS
=========================================================

Magic methods are special methods whose names begin with double
underscores (__). PHP automatically calls them when certain
events occur in a class.

They are used to add dynamic behavior to objects without calling
them explicitly.

Common Uses:
- Dynamic properties
- Dynamic methods
- Object cloning
- Serialization
- Object as string
- Object as function
- Debugging

-->

<?php

/*
|--------------------------------------------------------------------------
| 16.1 __get() & __set()
|--------------------------------------------------------------------------
|
| Triggered when accessing or assigning an undefined or
| inaccessible (private/protected) property.
|
| Common Uses:
| - Dynamic properties
| - Lazy loading
| - ORM Models (Laravel Eloquent)
|
*/

class Product
{
    private $data = [];

    public function __get($name)
    {
        echo "Getting '{$name}'\n";
        return $this->data[$name] ?? null;
    }

    public function __set($name, $value)
    {
        echo "Setting '{$name}' = '{$value}'\n";
        $this->data[$name] = $value;
    }
}

$product = new Product();

$product->price = 100;      // __set()
echo $product->price;       // __get()




/*
|--------------------------------------------------------------------------
| 16.2 __isset() & __unset()
|--------------------------------------------------------------------------
|
| Triggered when using:
|
| isset($object->property)
| unset($object->property)
|
| on inaccessible or undefined properties.
|
*/

class User
{
    private $data = [];

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function __unset($key)
    {
        unset($this->data[$key]);
    }
}

$user = new User();

$user->name = "Aman";

var_dump(isset($user->name)); // true

unset($user->name);

var_dump(isset($user->name)); // false




/*
|--------------------------------------------------------------------------
| 16.3 __call() & __callStatic()
|--------------------------------------------------------------------------
|
| __call()
| Triggered when calling an undefined or inaccessible
| instance method.
|
| __callStatic()
| Triggered when calling an undefined or inaccessible
| static method.
|
| Common Uses:
| - Dynamic APIs
| - Method forwarding
| - Laravel Facades
| - Proxy Classes
|
*/

class Logger
{
    public function __call($name, $arguments)
    {
        echo "Method '{$name}' not found.\n";
        echo implode(", ", $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        echo "Static method '{$name}' not found.\n";
        echo implode(", ", $arguments);
    }
}

$logger = new Logger();

$logger->save("User", "Created");

Logger::write("System Error");




/*
|--------------------------------------------------------------------------
| 16.4 __toString()
|--------------------------------------------------------------------------
|
| Triggered when an object is treated as a string.
|
| Example:
| echo $object;
|
| IMPORTANT:
| Must return a string.
| Returning anything else causes a Fatal Error.
|
*/

class Customer
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return "Customer: {$this->name}";
    }
}

$customer = new Customer("Aman");

echo $customer;




/*
|--------------------------------------------------------------------------
| 16.5 __invoke()
|--------------------------------------------------------------------------
|
| Triggered when an object is called like a function.
|
| Common Uses:
| - Callback handlers
| - Middleware
| - Strategy Pattern
| - Closures with state
|
*/

class Greeter
{
    public function __invoke($name)
    {
        echo "Hello {$name}";
    }
}

$greet = new Greeter();

$greet("Aman");




/*
|--------------------------------------------------------------------------
| 16.6 __clone()
|--------------------------------------------------------------------------
|
| Triggered when using:
|
| clone $object;
|
| PHP first performs a shallow copy,
| then automatically calls __clone().
|
*/

class Employee
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __clone()
    {
        echo "Object Cloned\n";

        $this->name .= " (Copy)";
    }
}

$emp1 = new Employee("John");

$emp2 = clone $emp1;

echo $emp1->name . PHP_EOL;

echo $emp2->name . PHP_EOL;




/*
|--------------------------------------------------------------------------
| 16.7 __sleep() & __wakeup()
|--------------------------------------------------------------------------
|
| Legacy serialization methods.
|
| Triggered by:
|
| serialize($object)
| unserialize($string)
|
| __sleep()
| Runs before serialization.
| Must return an array of property names.
|
| __wakeup()
| Runs after unserialization.
|
| Common Uses:
| - Reconnect database
| - Restore resources
| - Reinitialize values
|
| NOTE:
| Since PHP 7.4+, prefer __serialize()
| and __unserialize().
|
*/

class Book
{
    public $title;

    public $author;

    private $connection;

    public function __construct($title, $author)
    {
        $this->title = $title;

        $this->author = $author;

        $this->connection = "Database";
    }

    public function __sleep()
    {
        echo "Preparing Object...\n";

        return ['title', 'author'];
    }

    public function __wakeup()
    {
        echo "Object Restored\n";

        $this->connection = "Reconnect Database";
    }
}

$book = new Book("PHP", "John");

$data = serialize($book);

$newBook = unserialize($data);




/*
|--------------------------------------------------------------------------
| 16.8 __debugInfo()
|--------------------------------------------------------------------------
|
| Triggered when:
|
| var_dump($object)
|
| Allows customization of the debug output.
|
| Useful for hiding sensitive data.
|
*/

class Admin
{
    public $name = "Aman";

    private $password = "123456";

    public function __debugInfo()
    {
        return [
            'name' => $this->name,
            'password' => '******'
        ];
    }
}

$admin = new Admin();

var_dump($admin);




/*
|--------------------------------------------------------------------------
| 16.9 __serialize() & __unserialize()
|--------------------------------------------------------------------------
|
| Modern serialization methods (PHP 7.4+).
|
| Preferred over:
|
| __sleep()
| __wakeup()
|
| Triggered by:
|
| serialize($object)
| unserialize($string)
|
*/

class Student
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->name
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
    }
}

$student = new Student("Aman");

$data = serialize($student);

$newStudent = unserialize($data);




/*

=========================================================
Interview Revision
=========================================================

__get()
- Read inaccessible/undefined property

__set()
- Write inaccessible/undefined property

__isset()
- isset($object->property)

__unset()
- unset($object->property)

__call()
- Undefined instance method

__callStatic()
- Undefined static method

__toString()
- Object treated as string

__invoke()
- Object called like a function

__clone()
- clone $object

__sleep()
- Before serialize() (Legacy)

__wakeup()
- After unserialize() (Legacy)

__debugInfo()
- var_dump($object)

__serialize()
- Before serialize() (Modern)

__unserialize()
- After unserialize() (Modern)

=========================================================

*/