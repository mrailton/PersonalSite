<?php

declare(strict_types=1);

namespace App\Console\Commands\Reports;

use App\Jobs\Reports\ExpiringCertificatesAlertJob;
use Illuminate\Console\Command;

class CertificateExpiryReport extends Command
{
    protected $signature = 'reports:certificate-expiry';

    protected $description = 'Trigger Certificate Expiry Report';

    public function handle(): int
    {
        ExpiringCertificatesAlertJob::dispatch();
        return 0;
    }
}
