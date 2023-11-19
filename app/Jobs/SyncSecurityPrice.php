<?php

namespace App\Jobs;

use Throwable;
use Illuminate\Bus\Queueable;
use App\Services\SecurityPrice;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Mail\SendAlertErrorSynPrice;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

class SyncSecurityPrice implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $securityTypeName, public SendAlertErrorSynPrice $sendAlertErrorSynPrice)
    {
    }

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        return hash('sha256', $this->securityTypeName);
    }

    /**
     * Get the cache driver for the unique job lock.
     */
    public function uniqueVia(): Repository
    {
        return Cache::driver('redis');
    }

    /**
     * Execute the job.
     */
    public function handle(SecurityPrice $securityPrice): void
    {
        $securityPrice($this->securityTypeName);
    }

    public function failed(Throwable $exception): void
    {
        $this->sendAlertErrorSynPrice->__invoke($exception, $this->securityTypeName);
    }
}
