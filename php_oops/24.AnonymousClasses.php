
<!-- 
An anonymous class is a class without a name, defined on the fly — usually used once and immediately.
It’s like creating an object with custom behavior without declaring a full class. 
-->

<?php

$logger = new class {
    public function log($message) {
        echo "[LOG]: $message";
    }
};

$logger->log("Hello!"); // Output: [LOG]: Hello!

/* 

| Use Case                  | Reason                                    |
| ------------------------- | ----------------------------------------- |
| ✅ Simple one-time class  | No need to declare and name a class       |
| 🧪 Testing (mocking)      | Great for creating mock objects           |
| ⚙️ Lightweight strategies | Pass custom behavior quickly              |
| 🧩 Dependency Injection   | Inject behavior without extra class files |

*/

$gt = new class implements Greeter {
    public function greet($msg) {
        echo $msg;
    }
};

$gt->greet('Hello, user!');
$gt->greet('Welcome back, admin!');
