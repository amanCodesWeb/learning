
<!-- 
Static properties and methods belong to the class itself, not to any specific object (instance).
You do not need to create an object to access static members. They are shared across all instances of the class.

- Static Property
Declared with the static keyword.
Accessed using the scope resolution operator :: (double colon).
Shared among all objects of the class.

- Static Method
Also declared with static.
Can be called without creating an object.
Cannot use $this inside static methods because they’re not tied to an instance. rather use self::

-->

<?php

class MathHelper {
    public static $pi = 3.14159;

    public static function square($number) {
        return $number * $number;
    }
}

// Access static property without creating object
echo "Value of Pi: " . MathHelper::$pi . "<br>";

// Call static method without creating object
echo "Square of 4: " . MathHelper::square(4);
