<!-- 
A constructor is a special method that automatically runs when an object is created. It is commonly used to initialize properties.

public function __construct($param1, $param2) {
    $this->property1 = $param1;
    $this->property2 = $param2;
}

A destructor is a special method that is automatically called when an object is destroyed (usually at the end of script execution). It is often used to clean up resources (e.g., closing a file or DB connection).

public function __destruct() {
    // Cleanup code
}

-->

<?php

class Person
{
    private $name;

    // Constructor - runs when object is created
    public function __construct($name)
    {
        $this->name = $name;
        echo "Hello, $this->name!<br>";
    }

    public function sayHi()
    {
        echo "$this->name says hi.<br>";
    }

    // Destructor - runs when object is destroyed
    public function __destruct()
    {
        echo "$this->name is being removed from memory.<br>";
    }
}

// Create an object
$person1 = new Person("John");
$person1->sayHi();
