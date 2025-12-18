<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('action_id')->nullable();
            $table->string('texto');
            $table->string('url')->default('#');
            $table->string('route_name')->nullable();
            $table->integer('ordem')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('actions', function (Blueprint $table) {
            $table->foreign('action_id')->references('id')->on('actions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
