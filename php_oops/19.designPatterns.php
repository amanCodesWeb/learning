<!--

=========================================================
COMMON DESIGN PATTERNS
=========================================================

Design Patterns are reusable solutions to common software design
problems. They are not ready-made code but proven approaches for
structuring applications.

Benefits
---------
✔ Reusable solutions
✔ Loose coupling
✔ Better maintainability
✔ Easier testing
✔ Cleaner code

=========================================================
1. Singleton Pattern
=========================================================

Definition
----------
Ensures that only one instance of a class exists throughout the
application and provides a single global access point.

Diagram

        ┌───────────────┐
        │  Application  │
        └───────┬───────┘
                │
        getInstance()
                │
                ▼
        ┌───────────────┐
        │   Singleton   │
        └───────────────┘
               ▲
        Only one object

When to Use
-----------
• Database Connection
• Logger
• Configuration Manager
• Cache Manager

Advantages
----------
✔ Saves memory
✔ Prevents multiple instances
✔ Global access point

Disadvantages
-------------
✖ Hard to unit test
✖ Global state
✖ Can create tight coupling

Interview Definition
--------------------
Creates only one object of a class and provides a global access point
to access it.

=========================================================
2. Factory Pattern
=========================================================

Definition
----------
Creates objects without exposing the object creation logic to the client.
The client asks the factory for an object instead of creating it using new.

Diagram

                PaymentFactory
                      │
        ┌─────────────┴─────────────┐
        │                           │
        ▼                           ▼
  StripePayment              PayPalPayment

Client
   │
   ▼
Factory
   │
Returns Correct Object

When to Use
-----------
• Payment Gateways
• Notification Services
• Shipping Providers
• Report Generators

Advantages
----------
✔ Removes object creation logic
✔ Easy to extend
✔ Loose coupling

Interview Definition
--------------------
Factory decides which object should be created based on input.

=========================================================
3. Strategy Pattern
=========================================================

Definition
----------
Defines multiple algorithms and allows switching between them at runtime.

Diagram

           PaymentContext
                  │
        ┌─────────┴─────────┐
        ▼                   ▼
     Stripe             PayPal
        │                   │
     PaymentStrategy Interface

Runtime decides which strategy to use.

When to Use
-----------
• Payment methods
• Shipping calculation
• Tax calculation
• Sorting algorithms
• Discount calculation

Advantages
----------
✔ Easily replace algorithms
✔ Open for extension
✔ No if-else chains

Interview Definition
--------------------
Encapsulates different algorithms into separate classes and allows
switching them without modifying the client.

=========================================================
4. Observer Pattern
=========================================================

Definition
----------
One object (Subject) automatically notifies all registered
observers whenever its state changes.

Diagram

             Order
          (Subject)
               │
      notifyObservers()
               │
     ┌─────────┼─────────┐
     ▼         ▼         ▼
 Email      Logger     SMS
Observer   Observer  Observer

When to Use
-----------
• Laravel Events
• Email Notifications
• SMS Notifications
• Logging
• Real-time Updates

Advantages
----------
✔ Loose coupling
✔ Easy to add new observers
✔ Event-driven architecture

Interview Definition
--------------------
Subject automatically informs all subscribed observers whenever an
event occurs.

=========================================================
5. Decorator Pattern
=========================================================

Definition
----------
Adds new functionality to an object dynamically without modifying
its original class.

Diagram

        Request
           │
           ▼
   LoggingDecorator
           │
           ▼
   CacheDecorator
           │
           ▼
     Service Class

Each decorator wraps another object.

When to Use
-----------
• Logging
• Authentication
• Authorization
• Caching
• Compression
• Formatting

Advantages
----------
✔ Open/Closed Principle
✔ Dynamic behavior
✔ Flexible design

Interview Definition
--------------------
Adds additional functionality to an object at runtime without
changing its existing code.

=========================================================
6. Adapter Pattern
=========================================================

Definition
----------
Converts the interface of one class into another interface that the client expects.
It acts as a bridge between two incompatible classes without modifying either of them.

Diagram

            Client
               │
               ▼
          PaymentService
               │
               ▼
        PaymentAdapter
               │
        ┌──────┴──────┐
        ▼             ▼
     Stripe API   Razorpay API

The client only talks to the Adapter.
The Adapter translates the request to the appropriate API.

When to Use
-----------
• Integrating third-party APIs
• Legacy system integration
• Payment gateways
• Different storage providers
• Different SMS providers

Advantages
----------
✔ Reuse existing code
✔ Loose coupling
✔ Easy to replace third-party services
✔ Hides implementation differences

Interview Definition
--------------------
Adapter allows incompatible classes to work together by converting
one interface into another expected by the client.

=========================================================
Interview Revision
=========================================================

Singleton
----------
One object only.

Factory
--------
Creates objects.

Strategy
---------
Changes algorithm at runtime.

Observer
---------
One object notifies many objects.

Decorator
----------
Adds new behavior without modifying the original object.

=========================================================

Remember

Singleton  → One Object
Factory    → Object Creation
Strategy   → Change Behaviour
Observer   → Event Notification
Decorator  → Extend Behaviour / Adds functionality dynamically
Adapter    → Makes incompatible interfaces work together

=========================================================
