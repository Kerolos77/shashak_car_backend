<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchExchangeRateJob;
use App\Services\DigitalOceanBillingService;
use App\Services\GoogleCloudBillingService;

class SyncExpensesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expenses:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync automated expenses from DigitalOcean, Google Cloud, and fetch USD exchange rate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automated expenses sync...');

        // 1. Fetch Exchange Rate
        $this->info('Fetching exchange rate...');
        try {
            (new FetchExchangeRateJob())->handle();
            $this->info('Exchange rate sync completed.');
        } catch (\Exception $e) {
            $this->error('Exchange rate sync failed: ' . $e->getMessage());
        }

        // 2. Fetch DigitalOcean Invoices
        $this->info('Fetching DigitalOcean invoices...');
        try {
            $doResult = (new DigitalOceanBillingService())->fetchInvoices();
            if ($doResult['success']) {
                $this->info($doResult['message']);
            } else {
                $this->warn('DigitalOcean sync: ' . $doResult['message']);
            }
        } catch (\Exception $e) {
            $this->error('DigitalOcean sync failed: ' . $e->getMessage());
        }

        // 3. Fetch Google Cloud Billing
        $this->info('Fetching Google Cloud Billing...');
        try {
            $gcpResult = (new GoogleCloudBillingService())->fetchBilling();
            if ($gcpResult['success']) {
                $this->info($gcpResult['message']);
            } else {
                $this->warn('Google Cloud sync: ' . $gcpResult['message']);
            }
        } catch (\Exception $e) {
            $this->error('Google Cloud sync failed: ' . $e->getMessage());
        }

        $this->info('Automated expenses sync completed!');
        return Command::SUCCESS;
    }
}
