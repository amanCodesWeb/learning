<!--

=========================================================
USING COMPOSER IN OOP PROJECTS
=========================================================

Composer is PHP's dependency manager.

It helps you:
✔ Install third-party libraries
✔ Manage project dependencies
✔ Automatically load classes (Autoloading)
✔ Follow PSR-4 standards

Most modern PHP frameworks like Laravel use Composer.

=========================================================
1. Why Use Composer?
=========================================================

Without Composer
- Manually download libraries
- Manually require files
- Difficult to update packages

With Composer
✔ Install with one command

composer require package-name
✔ Update packages easily

composer update
✔ Automatic class loading

=========================================================
2. Composer and OOP
=========================================================

Instead of writing:
require 'User.php';
require 'Order.php';
require 'Payment.php';
require 'Invoice.php';

Composer automatically loads classes when needed.

You only write:
require 'vendor/autoload.php';

=========================================================
3. Project Structure
=========================================================

project/

│
├── app/
│     ├── Models/
│     ├── Services/
│     └── Controllers/
│
├── vendor/
│
├── composer.json
│
└── index.php

=========================================================
4. composer.json
=========================================================

{
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}

Meaning

Namespace
App\Services\UserService
↓
Folder
app/Services/UserService.php

=========================================================
5. Generate Autoload Files
=========================================================

Whenever you add a new class:
composer dump-autoload
Composer rebuilds the autoloader.

=========================================================
6. Using Classes
=========================================================

index.php

require 'vendor/autoload.php';
use App\Services\UserService;

$user = new UserService();

No require statements are needed.

=========================================================
7. Installing Packages
=========================================================

Install
composer require monolog/monolog

Remove
composer remove monolog/monolog

Update
composer update

=========================================================
8. Why Composer is Important in OOP
=========================================================

✔ Automatic class loading
✔ PSR-4 namespace support
✔ Dependency management
✔ Cleaner project structure
✔ Reusable packages

=========================================================
Interview Questions
=========================================================

Q. What is Composer?
PHP's dependency manager.

-------------------------------------

Q. Why is Composer used in OOP?
To manage dependencies and automatically load classes using PSR-4.

-------------------------------------

Q. What is vendor/autoload.php?
It is Composer's autoloader that automatically loads project classes
and installed packages.

-------------------------------------

Q. Why do we run composer dump-autoload?
To regenerate Composer's autoload mapping after adding, moving, or
renaming classes.

-------------------------------------

Q. What is composer.json?
The configuration file that defines project dependencies, autoloading,
and other Composer settings.

=========================================================
Memory Trick
=========================================================

Composer
↓
Packages + Autoloading + PSR-4

Remember:
Composer doesn't just install packages.
Its biggest role in OOP is automatic class loading.

=========================================================
