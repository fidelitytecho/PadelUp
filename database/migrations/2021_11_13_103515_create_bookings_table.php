<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->unsignedBigInteger('court_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('currency_id')->default(1);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->double('cost');
            $table->integer('duration');
            $table->string('comment', 255)->nullable();
            $table->string('label', 10)->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->boolean('payment_status')->default(false);
            $table->tinyInteger('payment_mode')->default(3);
            $table->integer('paymob_order_id')->nullable();
            $table->integer('paymob_transaction_id')->nullable();
            $table->integer('paymob_captured_transaction_id')->nullable();
            $table->timestamps();

            $table->foreign('court_id')
                ->references('id')
                ->on('courts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
