<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateExpiringMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Collection $certificates)
    {
    }

    public function build(): CertificateExpiringMail
    {
        return $this->markdown('mail.certificate-expiring-mail', ['certificates' => $this->certificates])->subject('Certificates Expiring Report');
    }
}
