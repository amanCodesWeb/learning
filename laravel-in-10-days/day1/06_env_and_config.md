# 06. Environment Configuration (.env) & Config

## Overview

Laravel uses two related systems for application configuration:

    .env
       ↓
    config/
       ↓
    Application

`.env` stores environment-specific values, while files inside
`config/` organize those values for the application.


## 1. .env File

The `.env` file contains configuration that can change between environments.

Examples:
    Local
    Development
    Staging
    Production

Example:
    APP_NAME=MyApp
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://localhost

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=myapp
    DB_USERNAME=root
    DB_PASSWORD=

    STRIPE_SECRET_KEY=xxxx
    SHOPIFY_API_KEY=xxxx


## 2. What Should Be Stored in .env?

Use `.env` for values that are:
- Environment-specific
- Sensitive
- Likely to change between environments
- Dependent on external services

Examples:
    Database credentials
    API keys
    API secrets
    Application URL
    Mail credentials
    Third-party service credentials
    Redis credentials


## 3. Never Commit Sensitive .env Values

The `.env` file normally should not be committed to Git.

Example:
    DB_PASSWORD=secret
    STRIPE_SECRET_KEY=xxxx

These values should remain outside the source code repository.

Laravel provides `.env.example` as a template containing the required variable names without actual secrets.


## 4. Configuration Files

Laravel's `config/` directory contains configuration files.

Examples:
    config/app.php
    config/database.php
    config/cache.php
    config/filesystems.php
    config/mail.php
    config/services.php


Example:

    // config/services.php

    'stripe' => [
        'secret' => env('STRIPE_SECRET_KEY'),
    ];


The application can then access it using:
    config('services.stripe.secret');


## 5. .env vs config()

The important relationship is:

    .env
      ↓
    config/*.php
      ↓
    config()
      ↓
    Application


Example:
    STRIPE_SECRET_KEY=xxxx

Then:

    // config/services.php

    'stripe' => [
        'secret' => env('STRIPE_SECRET_KEY'),
    ];

Then application code uses:
    $key = config('services.stripe.secret');


## 6. Why Not Use env() Everywhere?

Avoid doing this throughout application code:
    $key = env('STRIPE_SECRET_KEY');


Instead:
    $key = config('services.stripe.secret');


The main reason is Laravel's configuration caching.

When configuration is cached, Laravel uses the cached configuration instead of repeatedly reading environment values.

Therefore:
    .env
       ↓
    config files
       ↓
    config()
       ↓
    Application code


## 7. Example: Third-Party API

Suppose we integrate Shopify.

`.env`:

    SHOPIFY_API_KEY=xxxx
    SHOPIFY_API_SECRET=xxxx
    SHOPIFY_API_URL=https://example.myshopify.com


`config/services.php`:

    'shopify' => [
        'key' => env('SHOPIFY_API_KEY'),
        'secret' => env('SHOPIFY_API_SECRET'),
        'url' => env('SHOPIFY_API_URL'),
    ];


Application:

    $key = config('services.shopify.key');
    $secret = config('services.shopify.secret');
    $url = config('services.shopify.url');


## 8. Application Configuration

Laravel's `config/app.php` contains general application settings.

Examples include:
    Application name
    Environment
    Debug mode
    URL
    Timezone
    Locale


Environment values can be provided through `.env`.

Example:

    APP_NAME=MyApp
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://localhost


## 9. Database Configuration

Database configuration is mainly handled through:
    config/database.php


Example `.env`:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=myapp
    DB_USERNAME=root
    DB_PASSWORD=secret


Laravel's database configuration reads these values and uses them when establishing the database connection.


## 10. Configuration Cache

Laravel can cache configuration for better performance.

Command:
    php artisan config:cache


This creates a cached configuration that Laravel can load efficiently.

After changing `.env` values in an environment where configuration is cached, the configuration cache may need to be refreshed.

Useful commands:
    php artisan config:cache
    php artisan config:clear


## 11. Environment-Specific Configuration

The same application can use different values in different environments.

Example:

    Local:
    DB_DATABASE=myapp_local

    Production:
    DB_DATABASE=myapp_production


The application code remains the same.
Only the environment configuration changes.


## 12. Important Rule

Do not put application secrets directly inside source code.

Avoid:
    $stripeSecret = 'sk_live_xxxxx';


Prefer:
    .env
       ↓
    config/services.php
       ↓
    config('services.stripe.secret')


This makes configuration safer and easier to manage between environments.


## Remember

    .env
    → Environment-specific values and secrets

    config/
    → Organized application configuration

    env()
    → Reads environment values, mainly used inside config files

    config()
    → Reads configuration values from the application

Recommended pattern:

    .env
       ↓
    config/services.php
       ↓
    config('services.stripe.secret')
       ↓
    Application


### Must Know

- `.env` is environment-specific.
- Do not commit sensitive `.env` values.
- Use `.env` for API keys, secrets, database credentials, etc.
- Use `config/` to organize configuration.
- Use `config()` in application code.
- Avoid using `env()` directly throughout the application.
- Know `config:cache` and `config:clear`.