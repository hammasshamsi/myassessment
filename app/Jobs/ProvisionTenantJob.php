<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\OnboardingSession;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ProvisionTenantJob implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;
    public Tenant $tenant;
    public OnboardingSession $session;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->tenant = $tenant;
        $this->session = $session;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{

            $this->tenant->update(['provisioning_status' => 'in_progress']);

            // 1) Create isolated tenant DB
            $dbName = 'tenant_' . $this->tenant->id . '_' . $this->tenant->subdomain;
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");

            // 2) Run migrations for tenant
            Artisan::call('tenants:migrate', ['--database' => $dbName]);

            // 3) Seed initial user
            DB::connection($dbName)->table('users')->insert([
                'name' => $this->session->full_name,
                'email' => $this->session->email,
                'password' => $this->session->password, // should already be hashed
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4) Apply default config
            DB::connection($dbName)->table('settings')->insert([
                ['key' => 'timezone', 'value' => 'UTC'],
                ['key' => 'locale', 'value' => 'en'],
            ]);

            // Done
            $this->tenant->update(['provisioning_status' => 'completed']);

        } catch (\Throwable $e) {
            Log::error("Tenant provisioning failed: {$e->getMessage()}", ['tenant_id' => $this->tenant->id]);
            $this->tenant->update(['provisioning_status' => 'failed']);
            $this->fail($e);
        }




        // $session = OnboardingSession::find($this->sessionId);
        // $tenant = Tenant::create([
        //     'name' => $session->company_name,
        //     'subdomain' => $session->subdomain,
        //     'database' => 'tenant_'.$session->subdomain,
        //     'status' => 'provisioning',
        // ]);
        // //creating database on tenant subdomain
        // DB::statement('CREATE DATABASE IF NOT EXISTS '.$tenant->database);

        // //dynamically config tenant db
        // config()->set('database.connections.tenant', [
        //     'driver' => 'mysql',
        //     'host' => env('DB_host'),
        //     'database' => $tenant->database,
        //     'username' => env('DB_USERNAME'),            
        //     'password' => env('DB_PASSWORD')
        // ]);

        // DB::purge('tenant');
        // DB::reconnect('tenant');
        
        // //run migrations on tenant db
        // Artisan::call('tenants:artisan',[
        //     'artisanCommand' => 'migrate --database=tenant',
        //     '--tenants' => $tenant->id,
        // ]);

        // DB::connection('tenant')->insert([
        //     'name' => $session->full_name,
        //     'email' => $session->email,
        //     'password' => $session->password,
        //     'created_at' => now(),
        // ]);

        // $tenant->update([
        //     'status' => 'active',
        // ]);

    }
}
