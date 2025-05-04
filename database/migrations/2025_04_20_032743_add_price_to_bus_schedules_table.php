<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bus_schedules', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->after('schedule_id');
        });
    }

    public function down()
    {
        Schema::table('bus_schedules', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

};
