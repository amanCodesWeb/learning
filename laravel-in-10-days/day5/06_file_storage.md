# Laravel File Storage

## Definition

Laravel provides a **Filesystem** system for uploading, storing, retrieving, and deleting files.

Common uses:
- User profile images
- Product images
- Documents
- Invoices
- PDFs
- Private files

Laravel uses **storage disks** to define where files are stored.

---

## Storage Disks

Disks are configured in:
    config/filesystems.php

Common disks:
    local
    public

Laravel can also use cloud storage such as Amazon S3.

---

## Local Storage

Store a file:

    $path = $request->file('image')->store('images');

This stores the file using the configured disk.

Example result:

    images/abc123.jpg

---

## Public Storage

For files that should be accessible from the browser:

    $path = $request->file('image')->store(
        'images',
        'public'
    );

Files are stored under:

    storage/app/public/

---

## Storage Link

To make public storage accessible through the web:
    php artisan storage:link

This creates:

    public/storage
        ↓
    storage/app/public

Now a file can be accessed using:
    /storage/images/photo.jpg

---

## Uploading a File

Controller example:

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')
            ->store('products', 'public');

        // Save $path in database
    }

The database should usually store the **file path**, not the actual file.

Example:
    products/abc123.jpg

---

## Custom File Name

You can specify the filename:

    $path = $request->file('image')->storeAs(
        'products',
        'product-1.jpg',
        'public'
    );

---

## Getting the File URL

Using the Storage facade:

    use Illuminate\Support\Facades\Storage;

    $url = Storage::url($path);

Example:

    Storage::url('products/abc123.jpg');

Result:

    /storage/products/abc123.jpg

---

## Checking a File

Check whether a file exists:

    Storage::disk('public')->exists($path);

---

## Getting File Size

    $size = Storage::disk('public')->size($path);

---

## Deleting a File

    Storage::disk('public')->delete($path);

Multiple files:

    Storage::disk('public')->delete([
        'products/a.jpg',
        'products/b.jpg',
    ]);

---

## File Information

You can get the original uploaded filename:

    $request->file('image')->getClientOriginalName();

Get extension:

    $request->file('image')->getClientOriginalExtension();

Get MIME type:

    $request->file('image')->getMimeType();

However, for security and consistency, Laravel-generated filenames are generally preferable to trusting the original filename.

---

## File Validation

Always validate uploaded files.

Example:

    $request->validate([
        'image' => 'required|image|max:2048',
    ]);

Common rules:

    image
    mimes:jpg,jpeg,png
    max:2048
    required
    nullable

Example:

    'document' => 'required|mimes:pdf,doc,docx|max:5120'

---

## Public vs Private Files

### Public

Used when users can directly access the file.

Examples:

- Product images
- Profile images
- Public documents

Store using:

    'public'

### Private

Used when files should not be directly accessible.

Examples:

- Invoices
- Private documents
- Sensitive user files

Private files should normally be accessed through authorized controller logic.

---

## Storage Facade

Laravel provides:

    use Illuminate\Support\Facades\Storage;

Example:

    Storage::disk('public')->put(
        'test.txt',
        'Hello Laravel'
    );

Read a file:

    $content = Storage::disk('public')
        ->get('test.txt');

Delete:

    Storage::disk('public')
        ->delete('test.txt');

---

## File Upload Flow

    User uploads file
          ↓
    Request validation
          ↓
    $request->file()
          ↓
    Storage disk
          ↓
    File saved
          ↓
    Save file path in database
          ↓
    Generate URL when needed

Example:

    Product Image
          ↓
    storage/app/public/products/
          ↓
    Database:
    products/abc123.jpg
          ↓
    Storage::url()
          ↓
    Browser URL

---

## Important Best Practice

Do not usually store uploaded files directly inside:

    public/

Instead use Laravel's storage system:

    storage/app/public/

Then expose public files through:

    php artisan storage:link

This keeps file management centralized through Laravel's filesystem abstraction.

---

## File Storage vs Database

Usually:

    File
      ↓
    Storage

    File Path
      ↓
    Database

Example database value:

    products/abc123.jpg

Avoid storing the entire image binary in a normal database column unless there is a specific architectural reason.

---

## Key Points

- Laravel uses disks for file storage.
- Configure disks in `config/filesystems.php`.
- Use `Storage` for file operations.
- `store()` generates a unique filename.
- `storeAs()` allows a custom filename.
- Use `php artisan storage:link` for public files.
- Store the file path in the database.
- Validate uploaded files.
- Use private storage for sensitive files.
- Delete old files when replacing or permanently removing records.

---

## Interview Questions

### What is Laravel Filesystem?

Laravel's abstraction for storing, retrieving, and managing files across local or cloud storage.

### What is a disk?

A disk defines a storage location and its configuration.

### How do you upload a file?

    $path = $request->file('image')
        ->store('products', 'public');

### Why use `storage:link`?

It creates a symbolic link from `public/storage` to `storage/app/public`, allowing public files to be accessed through the web.

### `store()` vs `storeAs()`?

`store()` automatically generates a filename; `storeAs()` lets you specify the filename.

### Should you store the file itself in the database?

Usually no. Store the file in filesystem/cloud storage and save its path in the database.

### How do you delete a stored file?

    Storage::disk('public')->delete($path);

### Public vs Private storage?

Public files can be accessed by users through a URL. Private files require controlled/authorized access.