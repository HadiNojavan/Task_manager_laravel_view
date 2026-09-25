<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'App' }}</title>
    <style>
        body {  max-width: 800px; margin: 20px auto; padding:15px; background: #3e3e3a }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        header .actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        header form { margin: 0; }
    </style>
</head>
<body>

<header>
    <div>{{ $title ?? 'App' }}</div>

</header>

{{ $slot }}

</body>
</html>
