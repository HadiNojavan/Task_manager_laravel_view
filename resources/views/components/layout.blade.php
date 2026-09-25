<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'App' }}</title>
    <style>
        body { max-width: 800px; margin: 20px auto; padding: 15px; background: #3e3e3a; color: #fff; font-family: sans-serif; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #666; padding-bottom: 10px; margin-bottom: 20px; }
        header form { margin: 0; }
        a { color: #f8b803; }
    </style>
</head>
<body>

<header>
    <div>{{ $title ?? 'App' }}</div>

    @if (session()->has('token'))
        <form method="POST" action="{{ route('logout.web') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endif
</header>

{{ $slot }}

</body>
</html>
