<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Onboarding')</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        input { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
        button { padding: 10px 15px; }
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <h1>@yield('heading')</h1>

    @yield('content')
</body>
</html>