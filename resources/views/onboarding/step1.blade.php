@extends('layouts.onboarding')

@section('title', 'Step 1 - Acoount Information')
@section('heading', 'Step 1: Account Information')

@section('content')
<div class="container mx-auto p-4">
    <form action="{{ route('onboarding.step1') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="full_name" value="{{ old('full_name', $session->full_name ?? '') }}" name="full_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('full_name') <p>{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" value="{{ old('email', $session->email ?? '') }}" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <p>{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Continue to Step 2</button>
    </form>
</div>
@endsection