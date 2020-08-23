<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteAtToRoomsTable extends Migration
{

    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->softDeletes();
        });
    }


    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
