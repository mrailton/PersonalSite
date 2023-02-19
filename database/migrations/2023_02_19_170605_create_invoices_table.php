<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->date('issued_on');
            $table->date('due_on');
            $table->integer('amount')->default(0);
            $table->integer('balance')->default(0);
            $table->enum('status', ['draft', 'sent', 'overdue', 'paid', 'cancelled']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
