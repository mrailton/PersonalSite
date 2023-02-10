<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Certificates;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Certificates\StoreCertificateRequest;
use App\Models\Certificate;
use Illuminate\Http\RedirectResponse;

class StoreCertificateController extends Controller
{
    public function __invoke(StoreCertificateRequest $request): RedirectResponse
    {
        $data = [
            'name' => $request->validated('name'),
            'issued_by' => $request->validated('issued_by'),
            'issued_on' => $request->validated('issued_on'),
            'expires_on' => $request->validated('expires_on'),
            'notes' => $request->validated('notes'),
        ];

        if ($request->validated('image')) {
            $data['image'] = $request->file('image')->store('certs', ['disk' => 'uploads']);
        }

        Certificate::create($data);

        return redirect()->route('admin.certificates.list');
    }
}
