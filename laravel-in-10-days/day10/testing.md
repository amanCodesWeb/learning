## Laravel Testing

### Definition
Testing verifies that application features work correctly and continue working after code changes. Laravel supports automated tests using PHPUnit or Pest.

### Types of Tests
- **Unit Test:** Tests a small, isolated piece of code.
- **Feature Test:** Tests application behavior, such as routes, controllers, and database operations.

### Creating Tests

    php artisan make:test OrderTest
    php artisan make:test OrderServiceTest --unit

### Example: Feature Test

    use Tests\TestCase;

    class OrderTest extends TestCase
    {
        public function test_orders_page_loads(): void
        {
            $response = $this->get('/orders');

            $response->assertStatus(200);
        }
    }

Run tests:

    php artisan test

### Common Assertions

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $response->assertRedirect('/login');
    $this->assertDatabaseHas('orders', ['status' => 'paid']);

### Database Testing

Use `RefreshDatabase` to reset the test database when needed.

    use Illuminate\Foundation\Testing\RefreshDatabase;

    class OrderTest extends TestCase
    {
        use RefreshDatabase;
    }

Use factories to create test records and avoid depending on production data.

### Important Points
- Unit tests focus on isolated logic.
- Feature tests verify complete application behavior.
- Test successful cases, validation failures, authorization, and edge cases.
- Never run destructive tests against a production database.
