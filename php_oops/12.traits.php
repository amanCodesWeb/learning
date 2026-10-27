
<!-- 
A trait is a PHP mechanism for code reuse in single inheritance.
It allows you to share methods and properties across multiple classes without using inheritance.
Think of a Trait as a "mixin" — like reusable tools that you can plug into any class. 
-->

<?php

trait Logger {
    public function log($msg) {
        echo "Log: $msg\n";
    }
}

trait Notifier {
    public function notify($msg) {
        echo "Notify: $msg\n";
    }
}

class Order {
    use Logger, Notifier;
}

// If two traits have the same method, you must resolve the conflict using insteadof and as:

trait A {
    public function sayHello() {
        echo "Hello from A\n";
    }
}
trait B {
    public function sayHello() {
        echo "Hello from B\n";
    }
}

class Test {
    use A, B {
        B::sayHello insteadof A;
        A::sayHello as sayHelloFromA;
    }
}
