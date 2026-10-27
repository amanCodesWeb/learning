# Scenario-Based Interview Questions

## Scenario 1: An API is slow
**Possible causes:** N+1 queries, missing indexes, large responses, repeated API calls, or expensive processing.

**Solution:** Inspect queries, use eager loading, select required columns, add appropriate indexes, paginate results, and cache expensive operations when suitable.

## Scenario 2: The same order is created twice
**Possible causes:** Repeated requests, retries, or duplicate webhook delivery.

**Solution:** Use idempotency keys, database unique constraints, and transactions where appropriate. Do not rely only on frontend button disabling.

## Scenario 3: Payment succeeds but the order is not updated
**Solution:** Verify payment through a trusted server-side response or webhook. Make webhook processing idempotent, log failures, and implement retries or reconciliation.

## Scenario 4: Updating an order and its items fails halfway
**Solution:** Use `DB::transaction()` so related database changes commit together or roll back together.

## Scenario 5: An endpoint returns too many database queries
**Solution:** Check for N+1 problems and use `with()` to eager-load relationships. Use query logging or Laravel debugging tools to confirm the cause.

## Scenario 6: A user can access another user's order
**Solution:** Apply authorization policies or gates. Scope the order query to the authenticated user when appropriate; authentication alone is not authorization.

## Scenario 7: A queue job fails
**Solution:** Check logs and failed jobs, verify configuration and external dependencies, and configure appropriate retries and timeouts. Make jobs safe to retry.

## Scenario 8: API keys differ between environments
**Solution:** Store secrets in environment configuration and expose them through Laravel config files. Clear or rebuild cached configuration after changing production environment settings.

## Scenario 9: A file upload succeeds but the file cannot be accessed
**Solution:** Verify the storage disk, upload path, permissions, and public symlink or private-file access mechanism.

## Scenario 10: Production works differently from local development
**Solution:** Compare environment variables, PHP and extension versions, database settings, cached configuration, permissions, and logs. Reproduce the issue safely before changing production.



# Laravel Revision Checklist

## Fundamentals
- [ ] MVC architecture and request lifecycle
- [ ] Routes, controllers, middleware, and validation
- [ ] `.env`, config files, and Artisan commands
- [ ] Dependency injection and service container

## Database and Eloquent
- [ ] Migrations, query builder, and CRUD
- [ ] Relationships and eager loading
- [ ] N+1 problem, scopes, accessors, and mutators
- [ ] Factories, seeders, pagination, and soft deletes
- [ ] Transactions, indexes, and locking

## APIs and Security
- [ ] REST APIs and API Resources
- [ ] Authentication and authorization
- [ ] Sanctum, validation, and rate limiting
- [ ] Third-party APIs, webhooks, and idempotency
- [ ] Mass assignment, SQL injection, and CSRF

## Advanced Laravel
- [ ] Jobs, queues, events, and listeners
- [ ] Scheduler, notifications, and mail
- [ ] Cache, Redis, and collections
- [ ] Model events, observers, and file storage
- [ ] Exceptions, logging, and performance optimization

## Production and Quality
- [ ] Unit and feature testing
- [ ] Environment and configuration management
- [ ] Deployment, migrations, and backups
- [ ] Queue workers and scheduled tasks
- [ ] Debugging and production monitoring



# Final Interview Preparation

Before an interview, practise explaining each topic in your own words:

1. **What is it?** Give a clear definition.
2. **Why is it needed?** Explain the problem it solves.
3. **How does it work?** Describe the flow.
4. **When would you use it?** Give a real project example.
5. **What can go wrong?** Discuss edge cases and trade-offs.

Practise explaining a complete order workflow: validation, order creation, database transaction, payment integration, webhook handling, queued notifications, inventory updates, logging, and automated tests.