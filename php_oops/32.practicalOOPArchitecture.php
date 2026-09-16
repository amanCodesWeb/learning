<!--

=========================================================
PRACTICAL OOP ARCHITECTURE
=========================================================

As applications grow, putting all business logic inside Controllers
or Models makes the code difficult to maintain.

Practical OOP Architecture separates responsibilities into dedicated
classes.

Benefits
✔ Cleaner code
✔ Better maintainability
✔ Easier testing
✔ Reusable logic
✔ Follows SOLID principles

=========================================================
1. Service Classes
=========================================================

Definition
----------
A Service Class contains business logic.
Instead of writing complex logic inside Controllers, move it into a service.

Diagram

Request
   │
   ▼
Controller
   │
calls
   ▼
Service
   │
uses
   ▼
Models

Example

OrderController
↓
OrderService
↓
Order Model

Use Cases
• Order Processing
• Payment Logic
• Email Sending
• Invoice Generation

Benefits
✔ Thin Controllers
✔ Reusable logic
✔ Easy testing

Remember

Controller
↓
What should happen?

Service
↓
How should it happen?

=========================================================
2. Repository Pattern
=========================================================

Definition
----------
A Repository acts as a layer between your application and the database.
Instead of querying models directly, the application talks to the repository.

Diagram

Controller
    │
    ▼
Repository
    │
    ▼
Database

Example

Instead of
User::find($id);

Use
$userRepository->find($id);

Benefits
✔ Database logic in one place
✔ Easy to replace data source
✔ Easier unit testing
✔ Cleaner architecture

Common Methods
find()
create()
update()
delete()
all()

=========================================================
3. DTO (Data Transfer Object)
=========================================================

Definition
----------
A DTO is a simple object used to transfer data between layers.
It contains data only.
No business logic.

Diagram

Request
↓
DTO
↓
Service
↓
Repository

Example

RegisterUserDTO
name
email
password
Purpose

Move structured data safely between objects.

Benefits
✔ Strong typing
✔ Cleaner method parameters
✔ Easier validation
✔ Better readability

Remember
DTO = Data only.

=========================================================
4. Value Objects
=========================================================

Definition
----------
A Value Object represents a value rather than an entity.

It has:
✔ No identity
✔ Immutable data

Two Value Objects are equal if their values are equal.

Example
Money
Currency
Email Address
Phone Number
Date Range

Diagram

Order
│
▼
Money ₹150

Instead of
$order->price = 150;

Use
$order->price = new Money(150);

Benefits
✔ Immutable
✔ Validation in one place
✔ Better domain modeling

=========================================================
Architecture Flow
=========================================================

Request
↓
Controller
↓
Service
↓
Repository
↓
Database

DTO carries data between layers.
Value Objects represent domain values.

=========================================================
Interview Questions
=========================================================

Q. Why use Service Classes?
To move business logic out of Controllers.

-------------------------------------

Q. What is the Repository Pattern?
A layer that abstracts database operations from business logic.

-------------------------------------

Q. What is a DTO?
An object used only to transfer structured data.

-------------------------------------

Q. Does a DTO contain business logic?
No. Only data.

-------------------------------------

Q. What is a Value Object?
An immutable object that represents a value rather than an entity.

-------------------------------------

Q. Difference between Entity and Value Object?

Entity : Has identity.
Example :User (id = 1)

Value Object : No identity. Compared by value.
Example : Money(100)

