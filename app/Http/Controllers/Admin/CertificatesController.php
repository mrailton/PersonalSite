<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Certificates\StoreCertificateRequest;
use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CertificatesController extends Controller
{
    public function list(Request $request): View
    {
        $certificates = Certificate::query()->orderByRaw('ISNULL(expires_on), expires_on ASC')->get();

        return view('admin.certificates.list', ['certificates' => $certificates]);
    }

    public function create(Request $request): View
    {
        return view('admin.certificates.create');
    }

    public function store(StoreCertificateRequest $request): RedirectResponse
    {
        $certificate = Certificate::create([
            'name' => $request->validated('name'),
            'issued_by' => $request->validated('issued_by'),
            'issued_on' => $request->validated('issued_on'),
            'expires_on' => $request->validated('expires_on'),
            'certificate_number' => $request->validated('certificate_number'),
            'notes' => $request->validated('notes'),
        ]);

        if ($request->validated('file')) {
            $certificate->addMediaFromRequest('file')->toMediaCollection();
        }

        return redirect()->route('admin.certificates.list');
    }

    public function show(Request $request, Certificate $certificate): View
    {
        return view('admin.certificates.show', ['certificate' => $certificate]);
    }

    public function edit(Request $request, Certificate $certificate): View
    {
        return view('admin.certificates.edit', ['certificate' => $certificate]);
    }

    public function update(StoreCertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $certificate->name = $request->validated('name');
        $certificate->issued_by = $request->validated('issued_by');
        $certificate->issued_on = $request->validated('issued_on');
        $certificate->expires_on = $request->validated('expires_on');
        $certificate->certificate_number = $request->validated('certificate_number');
        $certificate->notes = $request->validated('notes');
        $certificate->save();

        return redirect()->route('admin.certificates.list');
    }

    public function delete(Request $request, Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.list');
    }

    public function viewFile(Request $request, Certificate $certificate)
    {
        return $certificate->getFirstMedia()->toInlineResponse($request);
    }

    public function downloadFile(Request $request, Certificate $certificate)
    {
        return $certificate->getFirstMedia();
    }
}
