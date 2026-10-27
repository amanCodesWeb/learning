
<!-- 
Autoloading lets PHP automatically load class files when they're needed, instead of you manually writing require or include.
This keeps your code clean, modular, and organized. 

PSR-4 maps namespaces to folder structures.
    "App\Models\User" → /app/Models/User.php

folder str.
project-root/
│
├── composer.json
├── app/
│   └── Models/
│       └── User.php

-->

<?
namespace App\Models;

class User {
    public function sayHello() {
        echo "Hello from User!";
    }
}


/* other file

require 'vendor/autoload.php';

use App\Models\User;

$user = new User();
$user->sayHello(); // Output: Hello from User!


*/