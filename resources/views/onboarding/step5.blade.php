@extends('layouts.onboarding')

@section('title', 'Step 5 - Confirmation')
@section('heading', 'Step 5: Confirmation & Review')

@section('content')
    <div class="container mx-auto p-4">
    
        <div class="mb-4">
            <p><strong>Name:</strong>{{$session->full_name}}</p>
        </div>
        <div class="mb-4">
            <p><strong>Email:</strong> {{$session->email}}</p>
        </div>
        <div class="mb-4">
            <p><strong>Company:</strong>{{$session->company_name}}</p>
        </div>
        <div class="mb-4">
            <p><strong>Subdomain:</strong> {{$session->subdomain}}</p>
        </div>
        <div class="mb-4">
            <p><strong>Address:</strong> {{$session->billing_name}}</p>    
        </div>
        <div class="mb-4">
            <p><strong>Country:</strong> {{$session->country}}</p>        
        </div>
        <div class="mb-4">
            <p><strong>Phone:</strong> {{$session->phone}}</p>
        </div>
        <form method="POST" action="{{ route('onboarding.step5') }}">
            @csrf
            <button type="submit">Confirm & Provision Tenant</button>
        </form>
    
</div>
@endsection