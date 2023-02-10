<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DownloadCertificateController extends Controller
{
    public function __invoke(Request $request, Certificate $certificate): Response
    {
        $fileName = $certificate->name . '.pdf';
        $file = Storage::disk('uploads')->get($certificate->file);
        $headers = [
            'Content-Disposition' => 'attachment; filename="'. $fileName .'"',
        ];

        return new Response($file, 200, $headers);
    }
}
