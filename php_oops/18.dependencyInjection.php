<!--

=========================================================
DEPENDENCY INJECTION (DI)
=========================================================

Dependency Injection (DI) is an OOP design technique where a class
receives its dependencies from the outside instead of creating
them itself.

Benefits:
- Loose Coupling
- Easier Unit Testing
- Flexible Code
- Better Maintainability
- Follows SOLID Principles

---------------------------------------------------------
What is a Dependency?
---------------------------------------------------------

A dependency is an object that another object needs to work.

Example:

Car
 └── Engine

Engine is the dependency of Car.

---------------------------------------------------------
Without Dependency Injection
---------------------------------------------------------

The class creates its own dependency.

Result:
❌ Tight Coupling

-->

<?php

class Engine
{
    public function start()
    {
        echo "Engine Started";
    }
}

class Car
{
    private Engine $engine;

    public function __construct()
    {
        // Bad Practice
        $this->engine = new Engine();
    }

    public function drive()
    {
        $this->engine->start();

        echo " Driving...";
    }
}

/*

Problems

- Car depends directly on Engine.
- Difficult to replace Engine.
- Hard to test.
- Violates Dependency Inversion Principle.

*/


/*
|--------------------------------------------------------------------------
| Constructor Injection (Recommended)
|--------------------------------------------------------------------------
|
| Dependency is provided through the constructor.
|
| Most commonly used approach.
|
*/

class Engine2
{
    public function start()
    {
        echo "Engine Started";
    }
}

class Car2
{
    private Engine2 $engine;

    public function __construct(Engine2 $engine)
    {
        $this->engine = $engine;
    }

    public function drive()
    {
        $this->engine->start();

        echo " Driving...";
    }
}

$engine = new Engine2();
$car = new Car2($engine);
$car->drive();




/*
|--------------------------------------------------------------------------
| Setter Injection
|--------------------------------------------------------------------------
|
| Dependency is injected after object creation.
|
| Useful for optional dependencies.
|
*/

class Car3
{
    private Engine2 $engine;

    public function setEngine(Engine2 $engine)
    {
        $this->engine = $engine;
    }

    public function drive()
    {
        $this->engine->start();
    }
}

$car = new Car3();
$car->setEngine(new Engine2());
$car->drive();



/*
|--------------------------------------------------------------------------
| Interface Injection (Best Practice)
|--------------------------------------------------------------------------
|
| Instead of depending on a concrete class,
| depend on an interface.
|
*/

interface EngineInterface
{
    public function start();
}

class PetrolEngine implements EngineInterface
{
    public function start()
    {
        echo "Petrol Engine Started";
    }
}

class ElectricEngine implements EngineInterface
{
    public function start()
    {
        echo "Electric Engine Started";
    }
}

class Car4
{
    private EngineInterface $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function drive()
    {
        $this->engine->start();

        echo " Driving...";
    }
}

$car1 = new Car4(new PetrolEngine());
$car2 = new Car4(new ElectricEngine());




/*
|--------------------------------------------------------------------------
| Why Interface Injection?
|--------------------------------------------------------------------------
|

Tomorrow if a new engine is introduced:
DieselEngine

No changes are required inside Car4.

Simply inject:
new DieselEngine()

as long as it implements EngineInterface.

This follows:
"Program to an Interface,
not an Implementation."

*/


/*
|--------------------------------------------------------------------------
| Real Laravel Example
|--------------------------------------------------------------------------
|

Controller

public function __construct(UserRepositoryInterface $users)
{
    $this->users = $users;
}

Laravel Service Container automatically creates the correct object and injects it.

No need to write:
new UserRepository();

*/


/*
|--------------------------------------------------------------------------
| Constructor vs Setter Injection
|--------------------------------------------------------------------------
|

Constructor Injection
✔ Required dependency
✔ Object always valid
✔ Most commonly used
✔ Recommended

Setter Injection
✔ Optional dependency
✔ Can change dependency later
✔ Useful when dependency is not always required

*/


/*
|--------------------------------------------------------------------------
| Interview Notes
|--------------------------------------------------------------------------
|

Dependency
Object required by another object.

Dependency Injection
Passing dependency from outside.

Tight Coupling
Class creates its own dependency.

Loose Coupling
Dependency comes from outside.

Best Practice
Depend on Interfaces
instead of Concrete Classes.

DI Types
1. Constructor Injection ⭐⭐⭐⭐⭐
2. Setter Injection ⭐⭐⭐
3. Interface Injection (Using Interface Type Hint)

Laravel
Uses Constructor Injection +
Service Container.

*/