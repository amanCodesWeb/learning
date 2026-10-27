
<!-- 
SOLID is an acronym for five design principles that make your code more maintainable, scalable, and flexible — especially in Object-Oriented Programming.

S : Single Responsibility Principle 
A class should have only one reason to change.

O : Open/Closed Principle
Classes should be open for extension, but closed for modification.

L : Liskov Substitution Principle
Subtypes must be substitutable for their base types.

I : Interface Segregation Principle
No client should be forced to depend on methods it doesn't use.

D : Dependency Inversion Principle
Depend on abstractions, not on concrete implementations.

-->

<?php

/* 1. Single Responsibility Principle (SRP) : “One class = One job.” */
class InvoicePrinter {
    public function print(Invoice $invoice) { /*...*/ }
}

class InvoiceSaver {
    public function save(Invoice $invoice) { /*...*/ }
}


/* 2. Open/Closed Principle (OCP) : “Extend behavior without modifying existing code.” */
interface Logger {
    public function log($message);
}

class FileLogger implements Logger {
    public function log($message) { /* write to file */ }
}

class DatabaseLogger implements Logger {
    public function log($message) { /* write to DB */ }
}


/* 3. Liskov Substitution Principle (LSP) : “If class B is a subtype of class A, it should behave like A.” */
class Bird {
    public function fly() {}
}

class Duck extends Bird {}
class Ostrich extends Bird { // ❌ Violates LSP
    public function fly() {
        throw new Exception("Can't fly");
    }
}


/* 4. Interface Segregation Principle (ISP) : “Don't force classes to implement methods they don’t need.” */
interface Printer {
    public function print();
}

interface Scanner {
    public function scan();
}

class MultiFunctionPrinter implements Printer, Scanner { /*...*/ }

class OldPrinter implements Printer { /*...*/ }


/* 5. Dependency Inversion Principle (DIP) : “Depend on abstractions, not concrete classes.” */
interface Mailer {
    public function send($msg);
}

class EmailService {
    private Mailer $mailer;
    
    public function __construct(Mailer $mailer) {
        $this->mailer = $mailer;
    }
}
