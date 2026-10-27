
<?php 

class Car {

    //properties or fields (attributes)
    // A property is a class variable that holds data or characteristics of an object.
    
    private $brand;
    private $color;

    // constructor 
    public function __construct($brand, $color = 'black')
    {
        $this->brand = $brand;
        $this->color = $color;

    }

    // Method (function)
    // A method is a function inside a class that defines the behavior or actions of an object.

    public function drive() {
        echo "The $this->color $this->brand is on the move.";
    }

}