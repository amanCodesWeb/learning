# Laravel Pagination

## Definition

**Pagination** divides a large set of database records into smaller pages instead of loading everything at once.

Example:

    1000 products
         ↓
    Page 1 → 20 products
    Page 2 → 20 products
    Page 3 → 20 products
    ...

Pagination improves:

- Performance
- Database queries
- Page loading speed
- User experience

---

## Basic Pagination

Using Eloquent:

    $users = User::paginate(10);

This returns **10 users per page**.

In the Blade view:

    @foreach ($users as $user)
        <p>{{ $user->name }}</p>
    @endforeach

    {{ $users->links() }}

Laravel automatically generates pagination links.

---

## Query Builder Pagination

Pagination also works with Query Builder:

    $users = DB::table('users')->paginate(10);

Then:

    {{ $users->links() }}

---

## Simple Pagination

If you only need Next/Previous links:

    $users = User::simplePaginate(10);

Use:

    {{ $users->links() }}

`simplePaginate()` does not calculate the total number of records.

---

## Cursor Pagination

For very large datasets:

    $users = User::cursorPaginate(10);

Cursor pagination is useful when working with large tables because it can be more efficient than traditional offset pagination.

Example:

    Page 1
       ↓
    Cursor
       ↓
    Page 2
       ↓
    Cursor
       ↓
    Page 3

---

## `paginate()` vs `simplePaginate()` vs `cursorPaginate()`

    paginate()
        ↓
    Full pagination
    Includes total pages/count

    simplePaginate()
        ↓
    Previous/Next navigation
    Does not calculate total count

    cursorPaginate()
        ↓
    Cursor-based pagination
    Good for large datasets

---

## Pagination with Conditions

You can combine pagination with `where()`:

    $products = Product::where('status', 'active')
        ->paginate(20);

Or:

    $products = Product::where('category_id', $categoryId)
        ->paginate(20);

---

## Pagination with Ordering

    $products = Product::orderBy('created_at', 'desc')
        ->paginate(20);

This displays the newest products first.

---

## Pagination with Search

Example:

    $products = Product::where('name', 'like', "%{$search}%")
        ->paginate(20);

When using query parameters, preserve them:

    {{ $products->withQueryString()->links() }}

This keeps parameters such as:

    ?search=phone&page=2

when navigating between pages.

---

## Custom Per-Page Value

You can allow the user to select the number of records:

    $perPage = request('per_page', 20);
    $products = Product::paginate($perPage);

Default:

    20 records per page

---

## Pagination API Response

Pagination is also useful for REST APIs:

    public function index()
    {
        $products = Product::paginate(20);
        return response()->json($products);
    }

Laravel includes pagination information such as:

    current_page
    last_page
    per_page
    total
    data

---

## Pagination with API Resources

For APIs, pagination can be combined with API Resources:

    $products = Product::paginate(20);
    return ProductResource::collection($products);

This allows you to control the structure of each item while Laravel provides pagination metadata.

---

## Pagination and N+1

Pagination does not automatically solve N+1 queries.

Bad:

    $orders = Order::paginate(20);

    foreach ($orders as $order) {
        echo $order->customer->name;
    }

Better:

    $orders = Order::with('customer')
        ->paginate(20);

This loads the required relationship efficiently.

---

## Pagination Methods

Common methods:

    paginate(20)
    simplePaginate(20)
    cursorPaginate(20)

Useful methods:

    $users->currentPage()
    $users->perPage()
    $users->total()
    $users->lastPage()
    $users->hasMorePages()

---

## Key Points

- Pagination divides large datasets into pages.
- `paginate()` provides full pagination information.
- `simplePaginate()` provides simpler Next/Previous navigation.
- `cursorPaginate()` is useful for large datasets.
- Use `links()` to display pagination links.
- `withQueryString()` preserves filters/search parameters.
- Use eager loading with pagination to avoid N+1 queries.
- Pagination is useful for both web pages and APIs.

---

## Interview Questions

### What is Pagination?

Pagination divides a large dataset into smaller pages to avoid loading all records at once.

### How do you paginate Eloquent results?

    User::paginate(10);

### How do you display pagination links?

    {{ $users->links() }}

### `paginate()` vs `simplePaginate()`?

`paginate()` calculates total records and pages. `simplePaginate()` only provides simpler Previous/Next navigation.

### When would you use cursor pagination?

For large datasets where offset-based pagination can become inefficient.

### How do you preserve search/filter parameters?

    {{ $users->withQueryString()->links() }}

### Does pagination prevent N+1 queries?

No. Use eager loading when relationships are needed:

    Order::with('customer')->paginate(20);