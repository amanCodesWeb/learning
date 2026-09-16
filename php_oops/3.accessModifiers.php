
<!-- 
Access modifiers control the visibility of class properties and methods — that is, where they can be accessed from (inside the class, outside, or in inherited classes).

Modifier	    Accessible From	                            Use Case
public          Anywhere	                                Open access
private	        Only within the class itself	            Full protection
protected	    Within the class and its child classes	    Inheritance-safe

-->

<?php

class User {
    public $name = "Alice";           // Can be accessed from anywhere
    private $password = "secret123";  // Can only be accessed inside this class
    protected $email = "alice@example.com"; // Accessible in this class and child classes

    public function showInfo() {
        echo "Name: $this->name<br>";
        echo "Password: $this->password<br>";
        echo "Email: $this->email<br>";
    }
}

$user = new User();

echo $user->name;         // ✅ Works (public)
// echo $user->password;  // ❌ Error (private)
// echo $user->email;     // ❌ Error (protected)

$user->showInfo();        // ✅ Works, all properties are accessed inside the class
