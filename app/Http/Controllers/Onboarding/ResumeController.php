<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\URL;

class ResumeController extends Controller
{
    public function __invoke(){

        $token = session('onboarding_token') ?? $request->query('token');

        if (! $token) {
            return redirect()->route('onboarding.step1');
        }

        $session = OnboardingSession::where('token', $token)->first();

        if (! $session) {
            return redirect()->route('onboarding.step1');
        }

        // save token for future use
        session(['onboarding_token' => $token]);

        if (! $session->full_name || ! $session->email) {
            // missing step 1 data -> resume Step 1 with signed link
            return redirect(URL::signedRoute('onboarding.step1', ['token' => $token]));
        }

        if (! $session->password) {
            // step2
            return redirect(URL::signedRoute('onboarding.step2', ['token' => $token]));
        }

        if (! $session->company_name || ! $session->subdomain) {
            // step3
            return redirect(URL::signedRoute('onboarding.step3', ['token' => $token]));
        }

        // if all previous complete, go to Step 4
        return redirect(URL::signedRoute('onboarding.step4', ['token' => $token]));


        // $token = session('onboarding_token');

        // if (!$token) {
        //     return redirect()->route('onboarding.step1');
        // }

        // $session = OnboardingSession::where('token', $token)->first();

        // if (!$session) {
        //     return redirect()->route('onboarding.step1');
        // }

        // if (!$session->password) {
        //     return redirect(URL::signedRoute('onboarding.step2', ['token' => $token]));
        // }

        // $url = URL::signedRoute('onboarding.step3', ['token' => $session->token]);

        // return redirect($url);
    }
}
