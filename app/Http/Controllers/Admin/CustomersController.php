<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function list(Request $request): View
    {
        $customers = Customer::all();

        return view('admin.customers.list', ['customers' => $customers]);
    }
}
