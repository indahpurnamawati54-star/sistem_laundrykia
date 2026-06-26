<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('cashier_id')->constrained('users');
            $table->foreignId('service_id')->constrained();
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['diterima', 'dalam_proses', 'selesai', 'diambil'])->default('diterima');
            $table->enum('payment_method', ['cash', 'transfer', 'e-wallet'])->nullable();
            $table->boolean('is_paid')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('process_started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};