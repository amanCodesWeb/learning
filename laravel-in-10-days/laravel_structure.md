Laravel Application
│
├── app/
│   │
│   ├── Console/
│   │   └── Commands/
│   │       → Contains custom Artisan commands used to automate application-specific CLI tasks.
│   │       → Command: php artisan make:command SendReports
│   │
│   ├── Events/
│   │   → Contains event classes that announce something happened in the application.
│   │   → Command: php artisan make:event OrderPlaced
│   │
│   ├── Exceptions/
│   │   → Contains custom exception classes used to represent and handle application errors.
│   │   → Command: php artisan make:exception PaymentException
│   │
│   ├── Http/
│   │   │
│   │   ├── Controllers/
│   │   │   → Handles HTTP requests and coordinates the required application logic.
│   │   │   → Command: php artisan make:controller ProductController
│   │   │
│   │   ├── Middleware/
│   │   │   → Filters or modifies requests before they reach the application.
│   │   │   → Command: php artisan make:middleware CheckRole
│   │   │
│   │   └── Requests/
│   │       → Contains Form Request classes for authorization and validation.
│   │       → Command: php artisan make:request StoreProductRequest
│   │
│   ├── Jobs/
│   │   → Contains tasks that can run immediately or asynchronously through queues.
│   │   → Command: php artisan make:job ProcessOrder
│   │
│   ├── Listeners/
│   │   → Contains classes that react to dispatched events.
│   │   → Command: php artisan make:listener SendOrderEmail
│   │
│   ├── Mail/
│   │   → Contains Mailable classes responsible for building application emails.
│   │   → Command: php artisan make:mail OrderConfirmation
│   │
│   ├── Models/
│   │   → Contains Eloquent models used to interact with database tables and relationships.
│   │   → Command: php artisan make:model Product
│   │
│   ├── Notifications/
│   │   → Contains notification classes for sending messages through different channels.
│   │   → Command: php artisan make:notification OrderShipped
│   │
│   ├── Policies/
│   │   → Contains authorization rules that determine what users can do with resources.
│   │   → Command: php artisan make:policy ProductPolicy
│   │
│   ├── Providers/
│   │   → Contains Service Providers used to register and bootstrap application services.
│   │   → Command: php artisan make:provider PaymentServiceProvider
│   │
│   ├── Rules/
│   │   → Contains custom validation rules for application-specific validation.
│   │   → Command: php artisan make:rule ValidPhoneNumber
│   │
│   ├── Services/
│   │   → Custom application directory for reusable business logic; Laravel does not create this by default.
│   │   → Command: No dedicated Laravel command.
│   │   → Create manually: mkdir app/Services
│   │
│   ├── Repositories/
│   │   → Custom application directory for separating data-access logic from business logic.
│   │   → Command: No dedicated Laravel command.
│   │   → Create manually: mkdir app/Repositories
│   │
│   ├── DTOs/
│   │   → Custom application directory for Data Transfer Objects used to pass structured data between layers.
│   │   → Command: No dedicated Laravel command.
│   │   → Create manually: mkdir app/DTOs
│   │
│   └── Contracts/
│       → Custom application directory commonly used for interfaces that define contracts between components.
│       → Command: No dedicated Laravel command.
│       → Create manually: mkdir app/Contracts
│
│
├── bootstrap/
│   │
│   ├── app.php
│   │   → Creates and configures the Laravel application during startup.
│   │   → Command: No make command; created by Laravel.
│   │
│   └── cache/
│       → Stores framework-generated cache files for faster application startup.
│       → Command: Generated automatically by Laravel.
│
│
├── config/
│   │
│   ├── app.php
│   │   → Stores general application configuration.
│   │
│   ├── auth.php
│   │   → Configures authentication guards and user providers.
│   │
│   ├── cache.php
│   │   → Configures cache stores such as file, database, and Redis.
│   │
│   ├── database.php
│   │   → Configures database connections and drivers.
│   │
│   ├── filesystems.php
│   │   → Configures local, public, S3, and other filesystem disks.
│   │
│   ├── logging.php
│   │   → Configures logging channels and log destinations.
│   │
│   ├── mail.php
│   │   → Configures mail drivers and email delivery.
│   │
│   ├── queue.php
│   │   → Configures queue connections and background job processing.
│   │
│   ├── services.php
│   │   → Stores configuration for third-party services and API credentials.
│   │
│   └── session.php
│       → Configures session storage and session behavior.
│
│       → Command for clearing config cache:
│         php artisan config:clear
│
│       → Command for caching configuration:
│         php artisan config:cache
│
│
├── database/
│   │
│   ├── factories/
│   │   → Defines reusable fake data structures for testing and development.
│   │   → Command: php artisan make:factory ProductFactory
│   │
│   ├── migrations/
│   │   → Defines version-controlled changes to the database structure.
│   │   → Command: php artisan make:migration create_products_table
│   │
│   └── seeders/
│       → Inserts predefined or generated data into the database.
│       → Command: php artisan make:seeder ProductSeeder
│
│
├── public/
│   │
│   ├── index.php
│   │   → Main HTTP entry point through which web requests enter Laravel.
│   │   → Command: Created by Laravel; no make command.
│   │
│   └── assets/
│       → Contains publicly accessible compiled CSS, JavaScript, images, and other assets.
│       → Command: Usually generated through Vite/build tooling rather than Artisan.
│
│
├── resources/
│   │
│   ├── css/
│   │   → Contains application CSS source files.
│   │   → Command: No dedicated Artisan command.
│   │
│   ├── js/
│   │   → Contains application JavaScript source files.
│   │   → Command: No dedicated Artisan command.
│   │
│   └── views/
│       → Contains Blade templates used to generate HTML.
│       → Command: No dedicated make command.
│
│
├── routes/
│   │
│   ├── web.php
│   │   → Defines browser-based routes using the web middleware group.
│   │   → Command: No make command; created by Laravel.
│   │
│   ├── console.php
│   │   → Defines console-related application commands and scheduling.
│   │   → Command: No make command; created by Laravel.
│   │
│   ├── api.php
│   │   → Defines stateless API routes when API routing is installed.
│   │   → Command: php artisan install:api
│   │
│   └── channels.php
│       → Defines authorization rules for broadcasting channels.
│       → Command: php artisan install:broadcasting
│
│
├── storage/
│   │
│   ├── app/
│   │   → Stores application-generated files and uploads.
│   │
│   ├── framework/
│   │   → Stores framework-generated files and caches.
│   │
│   └── logs/
│       → Stores application log files.
│
│
├── tests/
│   │
│   ├── Feature/
│   │   → Tests complete application behavior such as HTTP requests and database operations.
│   │   → Command: php artisan make:test ProductTest
│   │
│   └── Unit/
│       → Tests individual classes or isolated pieces of application logic.
│       → Command: php artisan make:test ProductTest --unit
│
│
├── .env
│   → Stores environment-specific values such as database credentials, API keys, and secrets.
│   → Command: No make command; created from .env.example during project setup.
│
├── .env.example
│   → Provides a template showing the environment variables required by the application.
│   → Command: No make command.
│
├── artisan
│   → CLI entry point used to execute Laravel Artisan commands.
│   → Example: php artisan list
│
├── composer.json
│   → Defines PHP dependencies, autoloading, and Composer project configuration.
│   → Command: Managed by Composer.
│
├── package.json
│   → Defines frontend dependencies and JavaScript build scripts.
│   → Command: Managed by npm.
│
└── vendor/
    → Contains PHP packages installed through Composer.
    → Command: composer install