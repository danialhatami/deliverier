<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_traces', function (Blueprint $table) {
            $table->renameColumn('lat', 'delivery_man_lat');
            $table->renameColumn('long', 'delivery_man_long');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_traces', function (Blueprint $table) {
            $table->renameColumn('delivery_man_lat', 'lat');
            $table->renameColumn('delivery_man_long', 'long');
        });
    }
};
