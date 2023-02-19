<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customers\StoreCustomerRequest;
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

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Customer::query()->create(['name' => $request->validated('name')]);

        return redirect()->route('admin.customers.list');
    }

    public function show(Request $request, Customer $customer): View
    {
        return view('admin.customers.show', ['customer' => $customer]);
    }

    public function update(StoreCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->name = $request->validated('name');
        $customer->save();

        return redirect()->route('admin.customers.list');
    }

    public function delete(Request $request, Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('admin.customers.list');
    }
}
