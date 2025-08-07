<!-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Step 2 - Set Password</title>
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Step 2: Create Password</h1>
    <form action="{{ route('onboarding.step1') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="full_name" value="{{ old('full_name') }}" name="full_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('full_name') <p>{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" value="{{ old('email') }}" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <p>{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Continue to Step 2</button>
    </form>
</div>
</body>
</html> -->


@extends('layouts.onboarding')

@section('title', 'Step 2 - Create Password')
@section('heading', 'Step 2: Create Password')

@section('content')
    <div class="container mx-auto p-4">
    <form action="{{ route('onboarding.step2') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password_confirmation') <p class="error">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Continue to Step 3</button>
    </form>
</div>

    
@endsection

