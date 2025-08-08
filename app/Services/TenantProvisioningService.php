<?php

namespace App\Services;

use App\Models\OnboardingSession;
use App\Models\Tenant;
use App\Jobs\ProvisionTenantJob;
use Illuminate\Support\Facades\DB;

class TenantProvisioningService
{
    public function provision(OnboardingSession $session): Tenant
    {
        // Idempotent: if tenant already exists, return it
        $tenant = Tenant::where('subdomain', $session->subdomain)->first();

        if (! $tenant) {
            DB::beginTransaction();
            try {
                $tenant = Tenant::create([
                    'company_name' => $session->company_name,
                    'subdomain'    => strtolower($session->subdomain),
                    'email'        => $session->email,
                    'provisioning_status' => 'pending',
                ]);

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }

        // Dispatch provisioning job (async)
        ProvisionTenantJob::dispatch($tenant, $session)->onQueue('provisioning');

        return $tenant;
    }
}