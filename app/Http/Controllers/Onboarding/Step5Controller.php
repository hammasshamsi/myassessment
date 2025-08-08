<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnboardingSession;
use App\Http\Requests\Step5Request;

class Step5Controller extends Controller
{
    public function show(Request $request){
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid signature.');
        }

        $token = $request->query('token') ?? session('onboarding_token');

        if (! $token) {
            return redirect()->route('onboarding.step1');
        }

        session(['onboarding_token' => $token]);

        $session = OnboardingSession::where('token', $token)->firstOrFail();

        return view('onboarding.step5', compact('session'));
    }
}
