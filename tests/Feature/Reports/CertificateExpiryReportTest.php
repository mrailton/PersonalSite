<?php

use App\Jobs\Reports\ExpiringCertificatesAlertJob;
use App\Mail\CertificateExpiringMail;
use App\Models\Certificate;
use App\Models\User;
use function Pest\Laravel\artisan;

test('sends the certificate expiry report to users that want to receive it', function () {
    Mail::fake();
    User::factory()->create(['receive_reports' => true]);
    Certificate::factory()->create(['expires_on' => now()]);
    Certificate::factory()->count(3)->create();

    artisan('reports:certificate-expiry');

    Mail::assertQueued(CertificateExpiringMail::class);
});

test('email content renders properly if there are certificates expiring in the next 3 months', function () {
    Certificate::factory()->create(['expires_on' => now()]);
    $certificates = (new ExpiringCertificatesAlertJob())->getExpiringCertificates();

    (new CertificateExpiringMail($certificates))->assertSeeInHtml($certificates[0]->name);
});

test('does not send any email if there are no certificates expiring', function () {
    Mail::fake();
    User::factory()->create(['receive_reports' => true]);
    Certificate::factory()->count(3)->create(['expires_on' => now()->addYear()]);

    artisan('reports:certificate-expiry');

    Mail::assertNothingQueued();
});
