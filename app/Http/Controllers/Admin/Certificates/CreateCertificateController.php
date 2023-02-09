<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CreateCertificateController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('admin.certificates.create');
    }
}
