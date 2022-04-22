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
        Schema::create('delivery_traces', function (Blueprint $table) {
            $table->id();
            $table->decimal('long', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->integer('delivery_request_id');

            $table->foreign('delivery_request_id')->references('id')->on('delivery_requests');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_traces');
    }
};
