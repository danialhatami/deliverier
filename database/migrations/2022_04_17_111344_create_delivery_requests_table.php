<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->increments('id')->unique();

            $table->decimal('sender_long', 10, 7);
            $table->decimal('sender_lat', 10, 7);
            $table->string('sender_address', 200);
            $table->string('sender_name', 100);
            $table->string('sender_mobile', 11);

            $table->string('receiver_name', 100);
            $table->string('receiver_mobile', 11);
            $table->string('receiver_address', 200);
            $table->decimal('receiver_long', 10, 7);
            $table->decimal('receiver_lat', 10, 7);

            $table->dateTime('sent_time')->nullable();
            $table->dateTime('received_time')->nullable();


            $table->integer('tracking_code')->unique();

            $table->integer('delivery_man_id')->nullable();
            $table->integer('partner_company_id');

            $table->timestamps();

            $table->softDeletes('canceled_at');


            $table->foreign('delivery_man_id')->references('id')->on('delivery_men');
            $table->foreign('partner_company_id')->references('id')->on('partner_companies');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_requests');
    }
};
