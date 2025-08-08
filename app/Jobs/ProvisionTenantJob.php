<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\OnboardingSession;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProvisionTenantJob implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;
    public Tenant $tenant;
    public OnboardingSession $session;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant, OnboardingSession $session)
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

            $this->tenant->update(['status' => 'in_progress']);

            $dbName = $this->tenant->database;
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");
            
            // dynamically configure tenant DB
            config()->set("database.connections.tenant", [
                'driver'    => 'mysql',
                'host'      => env('DB_HOST'),
                'port'      => env('DB_PORT', 3306),
                'database'  => $dbName,
                'username'  => env('DB_USERNAME'),
                'password'  => env('DB_PASSWORD'),
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            DB::purge('tenant');
            DB::reconnect('tenant');

            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path'     => '/database/migrations/tenant',
                '--force'    => true,
            ]);

            $existingUser = DB::connection('tenant')->table('users')->where('email', $this->session->email)->first();
            if (! $existingUser) {
                DB::connection('tenant')->table('users')->insert([
                    'name'       => $this->session->full_name,
                    'email'      => $this->session->email,
                    'password'   => Hash::needsRehash($this->session->password)
                        ? Hash::make($this->session->password)
                        : $this->session->password,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $existingSettings = DB::connection('tenant')->table('settings')->pluck('key')->toArray();
            $defaults = [
                ['key' => 'timezone', 'value' => 'UTC'],
                ['key' => 'locale', 'value' => 'en'],
            ];

            foreach ($defaults as $setting) {
                if (! in_array($setting['key'], $existingSettings)) {
                    DB::connection('tenant')->table('settings')->insert($setting);
                }
            }

            // Done
            $this->tenant->update(['status' => 'completed']);

        } catch (\Throwable $e) {
            Log::error("Tenant provisioning failed: {$e->getMessage()}", ['tenant_id' => $this->tenant->id]);
            $this->tenant->update(['status' => 'failed']);
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
