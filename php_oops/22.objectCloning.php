<!--

=========================================================
OBJECT CLONING (SHALLOW & DEEP COPY)
=========================================================

Object cloning creates a copy of an existing object instead of creating a new one manually.
PHP uses the clone keyword to duplicate an object.

When an object is cloned:
1. PHP creates a shallow copy.
2. If __clone() exists, PHP automatically calls it.

Syntax
$copy = clone $original;

=========================================================
1. Shallow Copy (Default)
=========================================================

Definition
----------
A shallow copy copies the object's properties.

If a property contains another object, both objects will reference
the SAME child object.

Diagram

Original
┌──────────────┐
│ Car          │
│ engine ──────┼────────────┐
└──────────────┘            │
                            ▼
                     ┌────────────┐
                     │ Engine     │
                     └────────────┘
                            ▲
┌──────────────┐            │
│ Car          │            │
│ engine ──────┼────────────┘
└──────────────┘
Clone

Both Car objects share the same Engine object.

PHP Example
-----------

class Engine
{
    public $type = "Petrol";
}

class Car
{
    public $engine;
}

$car1 = new Car();
$car1->engine = new Engine();

$car2 = clone $car1;
$car2->engine->type = "Electric";

echo $car1->engine->type;

// Output
Electric

Why?
Both objects point to the same Engine instance.

=========================================================
2. Deep Copy
=========================================================

Definition
----------
A deep copy duplicates both the parent object and every nested
object.

Each object has its own independent copy.

Diagram

Original

Car
 │
 ▼
Engine

Clone

Car
 │
 ▼
Engine

Different Engine objects

PHP Example
-----------

class Engine
{
    public $type = "Petrol";
}

class Car
{
    public $engine;

    public function __clone()
    {
        $this->engine = clone $this->engine;
    }
}

$car1 = new Car();
$car1->engine = new Engine();

$car2 = clone $car1;
$car2->engine->type = "Electric";

echo $car1->engine->type;

// Output
Petrol

Each Car now owns a different Engine object.

=========================================================
3. __clone() Magic Method
=========================================================

The __clone() method is automatically called after PHP performs
the default shallow copy.

Use it to:

✔ Clone nested objects
✔ Reset IDs
✔ Modify copied values
✔ Initialize clone-specific data

Example

class User
{
    public $id;
    public $name;

    public function __clone()
    {
        $this->id = null;
    }
}

=========================================================
Shallow vs Deep Copy
=========================================================

Shallow Copy

Object
 │
 ▼
Same Child Object

✔ Faster
✔ Less memory
✖ Shared references

------------------------------------------

Deep Copy

Object
 │
 ▼
New Child Object

✔ Completely independent
✖ Uses more memory

=========================================================
When to Use
=========================================================

Shallow Copy
✔ Immutable objects
✔ Shared resources
✔ Read-only objects

Deep Copy
✔ Editable copies
✔ Complex object graphs
✔ Independent duplicate objects

=========================================================

Real Laravel Example

Imagine an Order object containing an Address object.

Shallow Copy
Order1 ───► Address
Order2 ───► Address

Changing Order2's Address also changes Order1's Address.

Deep Copy
Order1 ───► Address1
Order2 ───► Address2

Both orders become completely independent.

=========================================================

Q. Why use `clone` instead of creating a new object with `new`?

Answer:
Use `clone` when you already have a fully configured object and want another object with almost the same data. It saves you from reinitializing all the properties.

Example (Email Template):

Email Template
├── Subject: Welcome
├── Body: Welcome to our platform...
└── Footer: Company Signature

            │
          clone
            ▼

Email 1                    Email 2
To: john@example.com       To: doe@example.com
Subject: Welcome           Subject: Welcome
Body: Same                 Body: Same

Only the recipient changes; everything else is copied.

Memory Tip:
`new`   → Create from scratch.
`clone` → Copy an existing object and modify only what is needed.

