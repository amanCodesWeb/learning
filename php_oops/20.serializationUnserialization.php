<!--

=========================================================
SERIALIZATION & UNSERIALIZATION
=========================================================

Serialization is the process of converting an object into a string
representation so it can be:

- Stored in a file
- Stored in a session
- Saved in a cache
- Sent over a network
- Stored in a database

Unserialization is the reverse process. It converts the serialized
string back into a PHP object.

---------------------------------------------------------
Flow
---------------------------------------------------------

Object
   │
serialize()
   │
   ▼
Serialized String
   │
unserialize()
   │
   ▼
Object

-->

<?php

class Book
{
    public $title;
    public $author;
}

$book = new Book();

$book->title = "1984";
$book->author = "George Orwell";


/*
|--------------------------------------------------------------------------
| Serialize
|--------------------------------------------------------------------------
|
| Converts the object into a string.
|
*/

$data = serialize($book);
echo $data;

/*

Output

O:4:"Book":2:{
    s:5:"title";
    s:4:"1984";
    s:6:"author";
    s:13:"George Orwell";
}

*/


/*
|--------------------------------------------------------------------------
| Unserialize
|--------------------------------------------------------------------------
|
| Converts the serialized string back into an object.
|
*/

$newBook = unserialize($data);

echo $newBook->title;      // 1984
echo $newBook->author;     // George Orwell


/*
|--------------------------------------------------------------------------
| Common Use Cases
|--------------------------------------------------------------------------
|

✔ PHP Sessions
✔ Cache (Redis, Memcached)
✔ Queue Jobs
✔ File Storage
✔ Network Communication

*/


/*
|--------------------------------------------------------------------------
| Security Warning
|--------------------------------------------------------------------------
|

Never unserialize untrusted user input.

Bad:
$data = $_POST['data'];
unserialize($data);

Reason:
It may lead to Object Injection vulnerabilities.

Prefer:
JSON when exchanging data with external systems.

*/


/*
|--------------------------------------------------------------------------
| Modern PHP
|--------------------------------------------------------------------------
|

PHP 7.4+

Prefer:
__serialize()
__unserialize()

instead of
__sleep()
__wakeup()

for custom serialization logic.

*/


/*
|--------------------------------------------------------------------------
| Interview Notes
|--------------------------------------------------------------------------
|

serialize()
Object → String

unserialize()
String → Object

Common Uses
- Sessions
- Cache
- Queues
- Files
- Network

Important
Never call unserialize() on untrusted input.
Use JSON for external APIs.

*/