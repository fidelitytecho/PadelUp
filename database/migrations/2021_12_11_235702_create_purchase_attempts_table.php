<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_attempts', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->boolean('payment_status')->default(false);
            $table->integer('paymob_order_id')->nullable();
            $table->longText('paymob_iframe_token')->nullable();
            $table->longText('paymob_callback')->nullable();
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_attempts');
    }
}
