<!--

=========================================================
OBJECT RELATIONSHIPS
=========================================================

Object relationships describe how one object is connected to another.

There are three common relationships:
1. Association
2. Aggregation
3. Composition

The main difference is ownership and object lifetime.

=========================================================
1. Association
=========================================================

Definition
----------
Association is a relationship where two objects know about each other and can work together, but neither owns the other.

Both objects can exist independently.

Diagram

        Teacher
            │
     teaches
            │
            ▼
        Student

Teacher can exist without Student.
Student can exist without Teacher.

Example
-------
A Customer places an Order.
The customer exists even if the order is deleted.
The order can also exist with only the customer's ID.

Characteristics
---------------
✔ Weak relationship
✔ No ownership
✔ Independent lifetime
✔ Most common relationship

PHP Example
------------

class Customer
{
}

class Order
{
    public function place(Customer $customer)
    {
        echo "Order placed";
    }
}

=========================================================
2. Aggregation
=========================================================

Definition
----------
Aggregation is a "Has-A" relationship where one object contains another object, but does not own its lifetime.

The contained object can exist independently.

Diagram

          Department
               ◇
               │
        contains
               │
        ┌──────┴──────┐
        ▼             ▼
     Employee     Employee

If Department is deleted,
Employees still exist.

Example
-------
Department → Employees
Team → Players
Library → Books
Company → Employees

Characteristics
---------------
✔ Weak ownership
✔ Shared objects
✔ Child survives parent
✔ Has-A relationship

PHP Example
------------

class Employee
{
}

class Department
{
    private array $employees = [];

    public function addEmployee(Employee $employee)
    {
        $this->employees[] = $employee;
    }
}

=========================================================
3. Composition
=========================================================

Definition
----------
Composition is a strong "Has-A" relationship where the parent owns the child.

If the parent is destroyed, the child is also destroyed.

Diagram

            House
              ◆
              │
         consists of
              │
       ┌──────┴──────┐
       ▼             ▼
     Room         Room

Delete House
↓
Rooms no longer exist.

Example
-------
House → Rooms
Car → Engine
Order → Order Items
Human → Heart

Characteristics
---------------
✔ Strong ownership
✔ Child depends on parent
✔ Child cannot exist alone
✔ Strongest relationship

PHP Example
------------

class Engine
{
}

class Car
{
    private Engine $engine;

    public function __construct()
    {
        $this->engine = new Engine();
    }
}

Engine belongs to Car.

If Car is destroyed,
its Engine is also destroyed.

=========================================================
Comparison
=========================================================

Association
Teacher -------- Student

Ownership: ❌ No
Lifetime: Independent
Relationship : Uses-A

---------------------------------------------------------

Aggregation
Department ◇----- Employee

Ownership: Partial
Lifetime: Independent
Relationship: Has-A

---------------------------------------------------------

Composition
Car ◆------ Engine

Ownership: Strong

Lifetime: Dependent

Relationship: Owns-A

=========================================================
Easy Way to Remember
=========================================================

Association

"I use you."

Teacher ↔ Student

----------------------------

Aggregation

"I have you."

Department → Employee
Employee can leave.

----------------------------

Composition

"I own you."

Car → Engine
Engine cannot exist without Car.

=========================================================

Memory Trick

Association = Uses
Aggregation = Has
Composition = Owns

=========================================================
