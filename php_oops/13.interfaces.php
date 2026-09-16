
<!-- 
An interface defines a contract:
It declares what methods a class must implement, but provides no actual code (no method bodies).
Think of it as a blueprint that classes must follow. 

📌 Why Use Interfaces?
    To enforce structure across multiple classes.
    To enable polymorphism: different classes that implement the same interface can be treated the same way.
    To decouple code (e.g., dependency injection, service containers).
    Ideal for frameworks and APIs where you want to force others to implement certain behavior.
-->

<?php

interface LoggerInterface {
    public function log($message);
}

class FileLogger implements LoggerInterface {
    public function log($message) {
        echo "Log to file: $message";
    }
}

/* 
PHP 8.0+ allows union types, nullable types, and static return types in interfaces.
PHP 8.1+ supports constants inside interfaces 
only contain methods and constants nothing else. 
*/

interface HasStatus {
    public const STATUS_ACTIVE = 'active';
}

class Person implements LoggerInterface, HasStatus{
    public function log($message) {
        echo "Log to file: $message";
    }
    public function status(){
        echo self::STATUS_ACTIVE;
        echo HasStatus::STATUS_ACTIVE;
    }
}


/* 
| Concept     | Description                                           |
| ----------- | ----------------------------------------------------- |
| Interface   | A contract — just defines **what** methods must exist |
| Cannot      | Have properties, private methods, or method bodies    |
| Implements  | A class can `implements` one or more interfaces       |
| Public only | All methods must be `public`                          |

*/