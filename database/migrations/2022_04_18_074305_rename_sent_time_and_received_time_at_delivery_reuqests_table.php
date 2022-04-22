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
        Schema::table('delivery_requests', function (Blueprint $table) {
            $table->renameColumn('sent_time', 'delivery_start_time');
            $table->renameColumn('received_time', 'delivery_end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            $table->renameColumn('delivery_start_time', 'sent_time');
            $table->renameColumn('delivery_end_time', 'received_time');
        });
    }
};
