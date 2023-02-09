<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListCertificatesController extends Controller
{
    public function __invoke(Request $request): View
    {
        $certificates = Certificate::all();

        return view('admin.certificates.list', ['certificates' => $certificates]);
    }
}
