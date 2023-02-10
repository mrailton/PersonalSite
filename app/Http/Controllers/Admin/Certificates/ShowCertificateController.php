<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowCertificateController extends Controller
{
    public function __invoke(Request $request, Certificate $certificate): View
    {
        return view('admin.certificates.show', ['certificate' => $certificate]);
    }
}
