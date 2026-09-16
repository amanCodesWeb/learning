
<!-- 
A class constant is a fixed value defined inside a class using the const keyword.
- It does not change once defined.
- It is accessed using the class name, not an object.
- Like static properties, you use the scope resolution operator :: to access it.
- Class constants are inherited by child classes.

-->

<?php

class Carc {
    const WHEELS = 4;

    public function showWheels() {
        echo "A car usually has " . self::WHEELS . " wheels.";
    }
}

// Accessing the constant without creating an object
echo Carc::WHEELS . "<br>";  // Output: 4

// Accessing from inside the class
$myCar = new Carc();
$myCar->showWheels();  // Output: A car usually has 4 wheels.
