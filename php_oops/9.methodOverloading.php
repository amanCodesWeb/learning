
<!-- 
Method Overriding allows a child class to provide a different implementation of a method that is already defined in its parent class.
- The method name must be the same.
- The child’s version will be used instead of the parent’s version when called from the child object.
- Enables custom behavior in subclasses. 
-->

<?php

class Animal {
    public function makeSound() {
        echo "Some generic animal sound<br>";
    }
}

class Dog extends Animal {
    // Overriding parent method
    public function makeSound() {
        echo "Woof! Woof!<br>";
    }
}

$pet = new Dog();
$pet->makeSound();  // Output: Woof! Woof!
