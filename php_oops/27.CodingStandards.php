
PSR stands for PHP Standards Recommendation — a set of coding standards created by the PHP-FIG (Framework Interop Group) to encourage consistency across PHP projects and frameworks like Laravel, Symfony, etc.

🧩 Most Important PSRs for OOP
PSR	Name	Purpose
PSR-1	Basic Coding Standard	Naming, file formatting, and class structure
PSR-2	Coding Style Guide	Code formatting (indentation, brackets, etc.)
PSR-4	Autoloading Standard	Autoloading classes via namespaces and folders
PSR-12	Extended Coding Style	Builds on PSR-1 and PSR-2 with stricter rules

📘 PSR-1 (Basic)
    Class names must be StudlyCaps
    Method names must be camelCase
    Files should use UTF-8 and only declare 1 class
    Side effects (like echo) should be avoided in files that declare classes

📘 PSR-2 (Code Style)
    4 spaces for indentation (no tabs)
    Opening braces on next line
    No space before ( in method calls, but space after if, foreach, etc.
<!-- 
class User {
    public function getName() {
        if ($this->name) {
            return $this->name;
        }
        return "Guest";
    }
}
 -->

📘 PSR-4 (Autoloading)

Defines how class files are organized using namespaces and directories.
<!-- 
// Namespace: App\Controllers
// File path: /app/Controllers/HomeController.php

namespace App\Controllers;

class HomeController {
    // ...
}
 -->
Composer will autoload this class based on the composer.json autoload rules.

📘 PSR-12 (Extended Style)
    Stricter than PSR-2
    One statement per line
    Visibility (public, private, etc.) is mandatory
    Blank line after namespace and before class
    Use declare(strict_types=1); when applicable

💡 Why Use PSRs?
Benefit	Description
✅ Consistency	Team and community can easily read & maintain code
⚙️ Compatibility	Easier to integrate 3rd-party packages
🚀 Tools Support	Works with tools like PHP-CS-Fixer, PHP_CodeSniffer, etc.
📦 Framework Ready	Most modern frameworks (Laravel, Symfony) follow PSRs