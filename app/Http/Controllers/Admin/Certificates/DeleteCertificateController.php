<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeleteCertificateController extends Controller
{
    public function __invoke(Request $request, Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.list');
    }
}
