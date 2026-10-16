# Laravel Deployment Basics

## Definition
Deployment is the process of making an application available in a production environment.

## Basic Deployment Steps

1. Upload or pull application code to the server.
2. Install production dependencies.
3. Configure the production `.env` file.
4. Generate the application key if needed.
5. Configure the database and run migrations.
6. Set correct file and directory permissions.
7. Configure the web server to serve Laravel's `public` directory.
8. Cache configuration, routes, and views where appropriate.
9. Restart queue workers and verify the application.

## Common Commands

    composer install --no-dev --optimize-autoloader

    php artisan key:generate

    php artisan migrate --force

    php artisan optimize

    php artisan queue:restart

**Important:** Run `key:generate` only when setting up a new application without an existing key. Do not casually regenerate the key on an existing production application, as encrypted data and sessions may become unreadable.

## Production Environment

Typical `.env` settings:

    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://example.com

Never commit `.env` or expose API keys, database credentials, or other secrets in source control.

## Storage and Permissions

    php artisan storage:link

This creates the public storage symlink when required.

Ensure the web server can write to `storage` and `bootstrap/cache`. Avoid giving unrestricted write permissions to the entire project.

## Queues and Scheduler

- Configure a process manager, such as Supervisor, for persistent queue workers.
- Configure the server's cron to run Laravel's scheduler every minute.

    * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

## Deployment Checklist
- [ ] Production `.env` configured.
- [ ] `APP_DEBUG=false`.
- [ ] Database migrations reviewed and applied safely.
- [ ] HTTPS enabled.
- [ ] Storage permissions and symlink configured.
- [ ] Queue workers and scheduler running.
- [ ] Logs, backups, and error monitoring configured.
- [ ] Application smoke-tested after deployment.
