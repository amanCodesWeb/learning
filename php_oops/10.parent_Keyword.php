
<!-- 
The parent:: keyword is used in a child class to call a method or constructor from its parent class.

It’s commonly used when:
    You override a method in the child, but still want to reuse the parent’s implementation.
    You want to call the parent class constructor from the child’s constructor.

cant call the private methods
-->

<?php

class Person {
    public function __construct($name) {
        echo "Person: $name\n";
    }
    
    public function language() {
        echo "speaks\n";
    }
}

class Employee extends Person {
    public function __construct($name, $job) {
        parent::__construct($name); // Call parent constructor
        echo "Job: $job\n";
        parent::language(); // call parent method
        echo "Hindi\n";
    }
    
    // can do it in other methods too
    /* 
    public function language() {
        parent::language(); // call parent method
        echo "Hindi\n";
    }
    */
}

$emp = new Employee('name', 'dev');

/* 
parent vs $this
$this->method() : Calls the method that belongs to the current object, even if it's overridden. Can be overridden by child classes.     
parent::method() : Directly calls the method from the parent class, skipping the override


class A {
    public function show() {
        echo "A::show\n";
    }
}

class B extends A {
    public function show() {
        echo "B::show\n";
    }

    public function test() {
        $this->show();     // calls B::show (normal behavior)
        parent::show();    // calls A::show (explicitly)
    }
}

$obj = new B();
$obj->test();

*/

