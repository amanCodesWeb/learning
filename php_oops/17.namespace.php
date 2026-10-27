<!--

=========================================================
PHP NAMESPACES
=========================================================

Namespaces help organize code and prevent naming conflicts.

A namespace is like a folder for your:
- Classes
- Interfaces
- Traits
- Enums
- Functions
- Constants

Without namespaces, two classes cannot have the same name.

Namespaces are heavily used in modern PHP frameworks like Laravel
and are required for PSR-4 autoloading.

-->

<?php

/*
|--------------------------------------------------------------------------
| 1. Basic Namespace
|--------------------------------------------------------------------------
|
| Declared using the namespace keyword.
|
*/

namespace Library;

class Book
{
    public function info()
    {
        echo "Library Book";
    }
}


/*
|--------------------------------------------------------------------------
| 2. Using Fully Qualified Class Name (FQCN)
|--------------------------------------------------------------------------
|

File: index.php

require 'Library/Book.php';

$book = new Library\Book();

*/


/*
|--------------------------------------------------------------------------
| 3. Importing Classes (use)
|--------------------------------------------------------------------------
|
| Instead of writing the full namespace every time,
| import it using "use".
|
*/

/*

namespace App;

use Library\Book;

$book = new Book();

*/


/*
|--------------------------------------------------------------------------
| 4. Alias (as)
|--------------------------------------------------------------------------
|
| Useful when two classes have the same name.
|
*/

/*

use Library\Book;
use Store\Book as StoreBook;

$book1 = new Book();

$book2 = new StoreBook();

*/


/*
|--------------------------------------------------------------------------
| 5. Import Multiple Classes
|--------------------------------------------------------------------------
*/

/*

use App\Models\User;
use App\Models\Post;
use App\Models\Category;

*/


/*
|--------------------------------------------------------------------------
| 6. Group Imports
|--------------------------------------------------------------------------
*/

/*

use App\Models\{
    User,
    Post,
    Category
};

*/


/*
|--------------------------------------------------------------------------
| 7. Import Functions
|--------------------------------------------------------------------------
|
| Namespaces can also contain functions.
|
*/

/*

namespace App\Helper;

function greet()
{
    echo "Hello";
}

-------------------

use function App\Helper\greet;

greet();

*/


/*
|--------------------------------------------------------------------------
| 8. Import Constants
|--------------------------------------------------------------------------
*/

/*

namespace App\Config;

const VERSION = "1.0";

-------------------

use const App\Config\VERSION;

echo VERSION;

*/


/*
|--------------------------------------------------------------------------
| 9. Global Namespace
|--------------------------------------------------------------------------
|
| Use "\" to access PHP built-in classes.
|
*/

namespace App;

$date = new \DateTime();
$exception = new \Exception();



/*
|--------------------------------------------------------------------------
| 10. __NAMESPACE__
|--------------------------------------------------------------------------
|
| Returns the current namespace.
|
*/

namespace App\Controllers;

echo __NAMESPACE__;

// Output:
// App\Controllers




/*
|--------------------------------------------------------------------------
| 11. Nested Namespaces
|--------------------------------------------------------------------------
|
| Namespaces can have multiple levels.
|
*/

namespace App\Http\Controllers;

class UserController
{
}




/*
|--------------------------------------------------------------------------
| 12. PSR-4 Relationship
|--------------------------------------------------------------------------
|

Namespace

App\Services\PaymentService
↓
Folder Structure

app/
    Services/
        PaymentService.php

Namespace should match the folder structure.

Composer's PSR-4 autoloader automatically loads the class.

*/


/*
|--------------------------------------------------------------------------
| Interview Notes
|--------------------------------------------------------------------------
|

Fully Qualified Class Name (FQCN)
Library\Book

Global Namespace
\DateTime
\Exception

Import Class
use Library\Book;

Alias
use Library\Book as LibraryBook;

Import Function
use function App\Helper\greet;

Import Constant
use const App\Config\VERSION;

Magic Constant
__NAMESPACE__

*/