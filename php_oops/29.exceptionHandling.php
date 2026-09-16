
<!-- 
Exceptions are special objects used to handle errors in a clean and controlled way using try–catch blocks.
Instead of breaking your program, exceptions let you catch errors gracefully. 
-->

<?

try {
    // Code that might throw an exception
    throw new Exception("Something went wrong!");
} catch (Exception $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
} finally {
    // Always runs (optional)
    echo "Cleaning up...";
}

//oops
class LoginException extends Exception {}

function login($user) {
    if ($user !== "admin") {
        throw new LoginException("Invalid user");
    }
}

try {
    login("guest");
} catch (LoginException $e) {
    echo "Login failed: " . $e->getMessage();
}


/* 
| Method                   | Description                   |
| ------------------------ | ----------------------------- |
| `$e->getMessage()`       | Error message                 |
| `$e->getCode()`          | Error code (if set)           |
| `$e->getFile()`          | File where exception occurred |
| `$e->getLine()`          | Line number                   |
| `$e->getTrace()`         | Stack trace as array          |
| `$e->getTraceAsString()` | Stack trace as string         |

*/

// multiple
try {
    // ...
} catch (LoginException | DatabaseException $e) {
    echo "Handled: " . $e->getMessage();
}
