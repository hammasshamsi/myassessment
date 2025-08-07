<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\OnboardingSession;

class ProvisionTenantJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $session = OnboardingSession::find($this->sessionId);
        $tenant = Tenant::create([
            'name' => $session->company_name,
            'subdomain' => $session->subdomain,
            'database' => 'tenant_'.$session->subdomain,
            'status' => 'provisioning',
        ]);
        //creating database on tenant subdomain
        DB::statement('CREATE DATABASE IF NOT EXISTS '.$tenant->database);

        //dynamically config tenant db
        config()->set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_host'),
            'database' => $tenant->database,
            'username' => env('DB_USERNAME'),            
            'password' => env('DB_PASSWORD')
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
        
        //run migrations on tenant db
        Artisan::call('tenants:artisan',[
            'artisanCommand' => 'migrate --database=tenant',
            '--tenants' => $tenant->id,
        ]);

        DB::connection('tenant')->insert([
            'name' => $session->full_name,
            'email' => $session->email,
            'password' => $session->password,
            'created_at' => now(),
        ]);

        $tenant->update([
            'status' => 'active',
        ]);

    }
}
