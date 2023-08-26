<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('play_set_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('merch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('academy_id')->nullable()->constrained()->cascadeOnDelete();
            $table->double('cost')->nullable();
            $table->string('label', 15)->default(('Pending'));
            $table->boolean('payment_status')->default(false);
            $table->tinyInteger('payment_mode')->default(3);
            $table->integer('paymob_order_id')->nullable();
            $table->integer('paymob_transaction_id')->nullable();
            $table->integer('paymob_captured_transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
