# Laravel Collections

## Definition

A **Collection** is a Laravel wrapper around an array that provides useful methods to filter, transform, sort, and process data easily.

Collections are commonly returned by Eloquent queries that retrieve multiple records.

## 1. Creating a Collection

    use Illuminate\Support\Collection;

    $items = collect([10, 20, 30, 40]);

    $users = User::all(); // Returns a collection of users

## 2. Common Collection Methods

    $numbers = collect([1, 2, 3, 4, 5]);

    $numbers->map(fn($n) => $n * 2);           // Transform items
    $numbers->filter(fn($n) => $n > 2);        // Keep matching items
    $numbers->first();                         // First item
    $numbers->last();                          // Last item
    $numbers->count();                         // Number of items
    $numbers->sort();                          // Sort items
    $numbers->unique();                        // Remove duplicates
    $numbers->sum();                           // Sum values
    $numbers->pluck('name');                   // Get values of a key
    $numbers->contains(3);                     // Check if value exists
    $numbers->values();                        // Reset numeric keys
    $numbers->toArray();                       // Convert to array

## 3. map() vs filter()

- `map()` transforms each item and returns a collection.
- `filter()` keeps items that satisfy a condition.

    $numbers = collect([1, 2, 3, 4]);

    $doubled = $numbers->map(fn($n) => $n * 2);
    // [2, 4, 6, 8]

    $filtered = $numbers->filter(fn($n) => $n > 2);
    // [3, 4]

## 4. Practical E-Commerce Example

    $orders = Order::all();

    $paidTotals = $orders
        ->where('status', 'paid')
        ->pluck('total');

    $totalRevenue = $paidTotals->sum();

This filters paid orders, extracts their totals, and calculates the total revenue.

**Note:** For large datasets, calculate totals in the database instead of loading every order into memory.

    $totalRevenue = Order::where('status', 'paid')->sum('total');

## 5. Collections vs Arrays

| Arrays | Collections |
|---|---|
| Native PHP data structure | Laravel collection object |
| Use PHP array functions | Use methods like `map()` and `filter()` |
| No built-in method chaining | Supports method chaining |
| Lightweight | Provides more convenient data operations |

## 6. Important Points

- Eloquent methods such as `get()` and `all()` return collections when retrieving multiple models.
- `first()` returns one item, not a collection.
- `map()` transforms items; `filter()` selects items.
- Collection methods generally return a new collection rather than modifying the original.
- Use `toArray()` to convert a collection into an array.
- Use database queries for filtering and aggregating large datasets whenever possible.

## 7. Interview Questions

**Q1. What is a Laravel Collection?**  
A wrapper around an array that provides convenient methods for working with data.

**Q2. What is the difference between `map()` and `filter()`?**  
`map()` transforms each item, while `filter()` keeps items matching a condition.

**Q3. What is `pluck()` used for?**  
To retrieve values for a specific key or column from a collection.

**Q4. What is the difference between `get()` and `first()`?**  
`get()` returns a collection of matching records; `first()` returns the first matching record or `null`.

**Q5. Why avoid loading all records for calculations?**  
It consumes memory and can be slower. Database methods such as `sum()`, `count()`, and `exists()` are often more efficient.