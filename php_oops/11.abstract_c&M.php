
<!-- 
An abstract class is a class that cannot be instantiated directly.
It is meant to be extended by other classes.

An abstract method is a method declared in an abstract class, but without any body.
Child classes must implement all abstract methods. 

🔧 When and Why You Use It:

    To enforce a contract (rules)
    → You want all child classes to implement specific methods (but implementation differs).

    To provide shared code
    → You have some base logic (like validation, logging, etc.) that all subclasses will use.

    To prevent direct instantiation
    → You don’t want the base class to be used on its own.

-->

<?php

abstract class Shape {
    // Abstract method - no body here
    abstract public function area();

    // Normal method
    public function describe() {
        echo "This is a shape.\n";
    }
}

class Circle extends Shape {
    private $radius;

    public function __construct($radius) {
        $this->radius = $radius;
    }

    // Must implement the abstract method
    public function area() {
        return pi() * pow($this->radius, 2);
    }
}

$circle = new Circle(5);
echo $circle->area();        // Output: 78.539816339745
$circle->describe();         // Output: This is a shape.

