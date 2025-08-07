<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </head>
    <body>
        <h1>Welcome to Z360 SaaS Platform</h1>

        <p>Choose how you'd like to begin:</p>

        <a href="{{ route('onboarding.new') }}">
            <button>New Organization Sign-Up</button>
        </a>

        <a href="{{ route('onboarding.resume') }}">
            <button>Resume Onboarding</button>
        </a>
    </body>
</html>
