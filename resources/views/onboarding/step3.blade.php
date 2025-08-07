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

@section('title', 'Step 3 - Company Details')
@section('heading', 'Step 3: Company Details')

@section('content')
    <div class="container mx-auto p-4">
    <form action="{{ route('onboarding.step3') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
            <input type="text" id="company_name" value="{{ old('company_name', $session->company_name ?? '') }}" name="company_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('company_name') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomain</label>
            <input type="text" id="subdomain" value="{{ old('subdomain', $session->subdomain ?? '') }}" name="subdomain" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('subdomain') <p class="error">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Continue to Step 4</button>
    </form>
</div>

    
@endsection

