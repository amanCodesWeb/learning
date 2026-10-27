# Blade Views

## Overview

Blade is Laravel's built-in templating engine for creating dynamic HTML pages.

Blade files use:

    .blade.php

Stored in:

    resources/views/

## Returning a View

```php
return view('home');
```

Nested view:

```php
return view('users.index');
```

## Passing Data

```php
return view('user', [
    'name' => 'Aman',
    'age' => 25,
]);
```

Blade:

```php
<h1>Hello {{ $name }}</h1>
<p>Age: {{ $age }}</p>
```

## Conditions

```php
@if ($user->is_admin)
    <p>Admin</p>
@else
    <p>User</p>
@endif
```

Common directives:

- `@if`
- `@else`
- `@elseif`
- `@unless`
- `@isset`
- `@empty`

## Loops

```php
@foreach ($users as $user)
    <p>{{ $user->name }}</p>
@endforeach
```

For empty collections:

```php
@forelse ($users as $user)
    <p>{{ $user->name }}</p>
@empty
    <p>No users found.</p>
@endforelse
```

## Layouts

Layout:

```php
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>
    @yield('content')
</body>
</html>
```

Child view:

```php
@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1>Welcome</h1>
@endsection
```

Main directives:

- `@extends`
- `@section`
- `@yield`

## Including Views

```php
@include('components.header')
```

Pass data:

```php
@include('components.user', ['user' => $user])
```

## Components

Reusable UI elements can be created as Blade components.

```php
<x-button>
    Save
</x-button>
```

## Forms

CSRF protection:

```php
<form method="POST" action="/users">
    @csrf

    <button type="submit">Save</button>
</form>
```

For PUT, PATCH, or DELETE:

```php
@csrf
@method('PUT')
```

## Validation Errors

```php
@error('email')
    <span>{{ $message }}</span>
@enderror
```

Old input:

```php
<input name="email" value="{{ old('email') }}">
```

## Key Points

- Blade files use `.blade.php`.
- Blade views are stored in `resources/views`.
- `{{ }}` displays escaped data.
- `@extends`, `@section`, and `@yield` are used for layouts.
- `@include` reuses views.
- `@csrf` protects forms.
- `@error` displays validation errors.
- Keep business logic out of Blade views.

## Interview Questions

**What is Blade?**  
Laravel's built-in templating engine.

**Where are Blade files stored?**  
`resources/views/`

**What is `{{ }}`?**  
It displays escaped output.

**What is `@extends`?**  
It allows a view to inherit a Blade layout.

**What is `@include`?**  
It includes another Blade view.

**What is `@csrf`?**  
It generates a CSRF token for form protection.