<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customers\StoreCustomerRequest;
use App\Http\Requests\Admin\Customers\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function list(Request $request): View
    {
        $customers = Customer::all();

        return view('admin.customers.list', ['customers' => $customers]);
    }

    public function create(Request $request): View
    {
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        // TODO: create customer

        return redirect()->route('admin.customers.list');
    }

    public function show(Request $request, Customer $customer): View
    {
        return view('admin.customers.show', ['customer' => $customer]);
    }

    public function edit(Request $request, Customer $customer): View
    {
        return view('admin.customers.edit', ['customer' => $customer]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        // TODO: Update customer

        return redirect()->route('admin.customers.list');
    }
}
