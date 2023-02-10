<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Certificates\StoreCertificateRequest;
use App\Models\Certificate;
use Illuminate\Http\RedirectResponse;

class UpdateCertificateController extends Controller
{
    public function __invoke(StoreCertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $certificate->name = $request->validated('name');
        $certificate->issued_by = $request->validated('issued_by');
        $certificate->issued_on = $request->validated('issued_on');
        $certificate->expires_on = $request->validated('expires_on');
        $certificate->notes = $request->validated('notes');
        $certificate->save();

        return redirect()->route('admin.certificates.list');
    }
}
