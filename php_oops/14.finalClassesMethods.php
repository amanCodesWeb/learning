
<!-- 
The final keyword in PHP is used to restrict inheritance or method overriding. It’s a way to protect certain behaviors in your code from being changed.

A final method cannot be overridden in a subclass.

-->

<?php

class Animal {
    final public function breathe() {
        echo "Breathing...\n";
    }
    
    public function speak() {
        echo "Animal sound\n";
    }
}

class Dog extends Animal {
    // ❌ This will cause a fatal error:
    // public function breathe() { echo "Dog breathing"; }
    
    public function speak() {
        echo "Bark\n";
    }
}
