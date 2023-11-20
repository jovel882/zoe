<?php

namespace App\Console\Commands;

use Throwable;
use App\Models\SecurityType;
use App\Jobs\SyncSecurityPrice;
use Illuminate\Console\Command;
use App\Services\Mail\SendAlertErrorSynPrice;

class SyncPricesFromExternal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoe:sync-prices-from-external {securityType=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes the prices from the \n
        external source of the specified security type or from all those registered in the DB if none is defined.';

    /**
     * Execute the console command.
     */
    public function handle(SecurityType $securityType, SendAlertErrorSynPrice $sendAlertErrorSynPrice)
    {
        $securityTypeName = $this->argument('securityType');
        try {
            $this->syncSecurityPrices($securityTypeName, $securityType, $sendAlertErrorSynPrice);
            $this->info('The command was successful!');
        } catch (Throwable $throw) {
            $this->error('The command was failed!');
            $sendAlertErrorSynPrice($throw, $securityTypeName);
        }
    }

    private function syncSecurityPrices(
        string &$securityTypeName,
        SecurityType $securityType,
        SendAlertErrorSynPrice $sendAlertErrorSynPrice
    ): bool
    {
        if ('all' == $securityTypeName) {
            foreach ($securityType->all() as $securityType) {
                $securityTypeName = $securityType->name;
                SyncSecurityPrice::dispatch($securityTypeName, $sendAlertErrorSynPrice);
            }
            return true;
        }

        SyncSecurityPrice::dispatch($securityTypeName, $sendAlertErrorSynPrice);
        return false;
    }
}
