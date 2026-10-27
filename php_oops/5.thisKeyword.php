
<!-- 
$this is a special variable inside a class that refers to the current object — the instance of the class that is calling a method or accessing a property.

It allows an object to access its own properties and methods from within the class. 
Without $this, you cannot access the object’s own data inside methods.

-->


<?php

class Car {
    private $brand;
    private $color;

    public function __construct($b, $c) {
        $this->brand = $b;   // Assign parameter to object's property
        $this->color = $c;
    }

    public function drive() {
        echo "The $this->color $this->brand is driving.";
    }
}

$myCar = new Car("Toyota", "Red");
$myCar->drive(); // Output: The Red Toyota is driving.
