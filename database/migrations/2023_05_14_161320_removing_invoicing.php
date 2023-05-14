<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::drop('invoice_items');
        Schema::drop('payments');
        Schema::drop('invoices');
        Schema::drop('customers');
    }
};
