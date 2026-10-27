# Laravel Model Factories

A Laravel class used to generate fake model data for testing, seeding, and development.

## 1. Factory Attributes

You can define default values in `definition()`:

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status' => 'active',
        ];
    }

You can override attributes when creating a model:

    User::factory()->create([
        'name' => 'Aman',
        'status' => 'inactive',
    ]);

The passed values override the factory defaults.

---

## 2. Factory States

States allow you to create predefined variations of a model.

    public function admin(): static
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

Use it:

    User::factory()->admin()->create();

Multiple states can be chained:

    User::factory()
        ->admin()
        ->active()
        ->create();

Example:

    public function inactive(): static
    {
        return $this->state([
            'status' => 'inactive',
        ]);
    }

---

## 3. Factory Relationships

Factories can automatically create related models.

Example:

    Post::factory()
        ->for(User::factory())
        ->create();

This creates:

    User
      ↓
    Post

You can also use an existing model:

    $user = User::factory()->create();

    Post::factory()
        ->for($user)
        ->create();

---

## 4. Creating Has-Many Relationships

Suppose:

    User hasMany Post

You can create a user with multiple posts:

    $user = User::factory()
        ->has(Post::factory()->count(3))
        ->create();

Result:

    User
      ├── Post
      ├── Post
      └── Post

You can also define the relationship name:

    User::factory()
        ->has(Post::factory()->count(3), 'posts')
        ->create();

---

## 5. Creating Multiple Records

Create one:
    User::factory()->create();

Create multiple:
    User::factory()->count(10)->create();

Or:
    User::factory(10)->create();

Both create 10 users.

---

## 6. Factory Sequences

`sequence()` allows values to change for each generated record.

    User::factory()
        ->count(3)
        ->sequence(
            ['role' => 'admin'],
            ['role' => 'manager'],
            ['role' => 'customer']
        )
        ->create();

Result:

    User 1 → admin
    User 2 → manager
    User 3 → customer

Useful when different records need different predefined values.

---

## 7. Factory Callbacks

Factories provide lifecycle callbacks.

Common callbacks:
    afterMaking()
    afterCreating()

Example:

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            // additional logic
        });
    }

`afterMaking()` runs after the model is created in memory.
`afterCreating()` runs after the model is saved to the database.

---

## 8. Reusing Existing Models

If multiple related records should use the same existing model, you can create the model once and reuse it.

    $user = User::factory()->create();

    Post::factory()
        ->count(5)
        ->for($user)
        ->create();

All 5 posts belong to the same user.

---

## 9. Factory in Tests

Factories are heavily used in feature and unit tests.

Example:

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/dashboard');

    $response->assertStatus(200);

Create test data:

    User::factory()->count(10)->create();

Then test:

    $this->assertDatabaseCount('users', 10);

Factories make tests easier because you don't have to manually insert database records.

---

## 10. Factory with Authentication

Common Laravel testing pattern:

    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/dashboard');

This creates a fake user and authenticates the test as that user.

---

## 11. Factory with E-Commerce Data

Example:

    $customer = User::factory()->create();

    $order = Order::factory()
        ->for($customer)
        ->create();

    Product::factory()
        ->count(5)
        ->create();

This is useful for quickly generating realistic development/test data.

Example structure:

    User
      ↓
    Order
      ↓
    Order Items
      ↓
    Products

---

## 12. Factory vs Faker

### Factory

Defines how a model should be generated.

    User::factory()->create();

### Faker

Generates fake values.

    fake()->name();
    fake()->email();

Relationship:

    Factory
       ↓
    uses Faker
       ↓
    generates fake model data

---

## 13. Factory vs Seeder

### Factory

Defines the structure of generated data.

    UserFactory
        ↓
    name
    email
    password

### Seeder

Controls when and how much data is inserted.
    User::factory()->count(100)->create();

Simple rule:
    Factory = What does the fake data look like?
    Seeder  = What data should be created?

---

## 14. Factory vs Model

### Model

Represents database data and provides ORM functionality.
    User extends Model

### Factory

Generates model instances/data.
    User::factory()

Simple relationship:

    Model
      ↓
    Database record

    Factory
      ↓
    Generates model data

---

## 15. Important Factory Methods

    factory()->create()
        Creates and saves model.

    factory()->make()
        Creates model without saving.

    factory()->count(10)
        Generates multiple models.

    factory()->state(...)
        Changes factory attributes.

    factory()->for(...)
        Defines a belongs-to relationship.

    factory()->has(...)
        Defines a has-many relationship.

    factory()->sequence(...)
        Provides different values for generated records.

---

## 16. Typical Development Flow

    Create Factory
          ↓
    Define fake attributes
          ↓
    Create relationships
          ↓
    Use Factory in Seeder/Test
          ↓
    Generate database records

Example:
    php artisan make:factory ProductFactory

Then:
    Product::factory()->count(50)->create();

---

## 17. Key Points

- Factories generate model data.
- Faker generates fake attribute values.
- `create()` saves records.
- `make()` does not save records.
- `state()` creates reusable variations.
- `for()` defines belongs-to relationships.
- `has()` defines has-many relationships.
- `sequence()` provides different values for each record.
- Factories are heavily used in testing and seeders.
- Factories should generate realistic but predictable test data.

---

## Interview Questions

### What is a Model Factory?

A Laravel class used to generate fake model data for testing, seeding, and development.

### What is Faker?

Faker generates fake values such as names, emails, addresses, numbers, and dates.

### `make()` vs `create()`?

`make()` creates a model instance without saving it.
`create()` creates and saves the model to the database.

### How do you create 100 users?

    User::factory()->count(100)->create();

### How do you create related models?

Use `for()` for belongs-to relationships and `has()` for has-many relationships.

### What is a Factory State?

A reusable modification of factory data, such as `admin()`, `inactive()`, or `verified()`.

### Factory vs Seeder?

Factory defines the generated data; Seeder controls what data gets inserted and how much.

### Why are factories useful in testing?

They quickly create realistic database records without manually inserting test data.