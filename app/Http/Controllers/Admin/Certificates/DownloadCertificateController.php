<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class DownloadCertificateController extends Controller
{
    public function __invoke(Request $request, Certificate $certificate)
    {
        return $certificate->getFirstMedia();
    }
}
