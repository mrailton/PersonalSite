<?php

declare(strict_types=1);

namespace App\Jobs\Reports;

use App\Mail\CertificateExpiringMail;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ExpiringCertificatesAlertJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $certificates = $this->getExpiringCertificates();

        if (count($certificates) > 0) {
            Mail::to($this->getRecipients())->queue(new CertificateExpiringMail($certificates));
        }
    }

    private function getExpiringCertificates(): Collection
    {
        return Certificate::query()->where('expires_on', '<', now()->addMonths(3))->get();
    }

    private function getRecipients(): Collection
    {
        return User::query()->where('receive_reports', '=', 1)->get();
    }
}
