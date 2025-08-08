<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnboardingSession;
use App\Http\Requests\Step5Request;
use App\Services\TenantProvisioningService;

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

    public function store(Step5Request $request, TenantProvisioningService $service)
    {
        $token = session('onboarding_token');
        $session = OnboardingSession::where('token', $token)->firstOrFail();

        $tenant = $service->provision($session);

        return route('onboarding.step5');
        // return redirect("https://{$tenant->domain}." . config('app.main_domain') . "/login")
        //     ->with('success', 'Your tenant is being provisioned.');
    }
}
