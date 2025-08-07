<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\URL;

class ResumeController extends Controller
{
    public function __invoke(){
        $token = session('onboarding_token');

        if (!$token) {
            return redirect()->route('onboarding.step1');
        }

        $session = OnboardingSession::where('token', $token)->first();

        if (!$session) {
            return redirect()->route('onboarding.step1');
        }

        if (!$session->password) {
            return redirect(URL::signedRoute('onboarding.step2', ['token' => $token]));
        }

        // Later: add more step tracking like company/subdomain, billing, etc.

        return redirect()->route('onboarding.step3'); // Next step fallback
    
    }
}
