
<!-- 

A class is like a blueprint for creating objects. It defines properties (variables) and methods (functions) that its objects will have.
An object is an instance of a class. It’s created using the new keyword and can access the class's properties and methods. 

-->


<?php 

// Define a class
class Car {

    //properties or fields (attributes)
    private $brand;
    private $color;

    // constructor 
    public function __construct($brand, $color = 'black')
    {
        $this->brand = $brand;
        $this->color = $color;

    }

    // Method (function)
    public function drive() {
        echo "The $this->color $this->brand is on the move.";
    }

}


// Create an object (instance) of the classed
$myCar = new Car('Toyota', 'Red');

// Call a method
$myCar->drive();










