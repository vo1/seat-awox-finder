<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateAwoxersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awoxers', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('added_by')->nullable();
            $table->datetime('created_at')->default(DB::raw('NOW()'));
            $table->datetime('updated_at')->default(DB::raw('NOW()'));
            $table->datetime('pinged_at')->default(DB::raw('DATE_SUB(NOW(), INTERVAL 1 DAY)'));
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('awoxers');
    }
}
