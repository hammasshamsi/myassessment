<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Step2Request;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\Hash;


class Step2Controller extends Controller
{
    public function show(Request $request)
    {
        if(!$request->hasValidSignature()){
            abort(403, 'Invalid or Expired Link.');
        }
        $token = $request->query('token');
        $session = OnboardingSession::where('token', $token)->firstorFail();
        session(['onboarding_token' => $token]);
        return view('onboarding.step2');
    }

    public function store(Step2Request $request)
    {
        $token = session('onboarding_token');
        if(!$token){
            abort(403, 'Invalid or Expired Session./nToken not found in session.');
        }
        $session = OnboardingSession::where('token', $token)->firstOrFail();
        $session->update([
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect()->route('onboarding.step3');
    }
}
