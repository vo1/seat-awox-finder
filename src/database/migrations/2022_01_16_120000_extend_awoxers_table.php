<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class ExtendAwoxersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('awoxers', function (Blueprint $table) {
            $table->text('affiliation')->nullable();
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('awoxers', function (Blueprint $table) {
            $table->dropColumn('affiliation');
            $table->dropColumn('reason');
        });
    }
}
