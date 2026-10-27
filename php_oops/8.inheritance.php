
<!-- 
Inheritance allows a class (called the child class or subclass) to inherit properties and methods from another class (called the parent class or base class).
This promotes code reusability, cleaner architecture, and logical hierarchies. 
-->

<?php

// Parent class
class Vehicle {
    public $brand;

    public function honk() {
        echo "Beep beep!<br>";
    }
}

// Child class
class Cars extends Vehicle {
    public $color;

    public function __construct($brand, $color)
    {
        $this->$brand = $brand;
        $this->$color = $color;
        
    }

    public function drive() {
        echo "The $this->color $this->brand is driving.<br>";
    }
}

// Create object of child class
$myCar = new Cars('Toyota', 'Black');

$myCar->honk();   // Method from parent class
$myCar->drive();  // Method from child class

