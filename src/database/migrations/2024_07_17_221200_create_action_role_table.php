<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_role', function (Blueprint $table) {
            $table->foreignUuid('action_id')->constrained('actions', 'id');
            $table->foreignUuid('role_id')->constrained('roles', 'id');
            $table->primary(['action_id', 'role_id']);
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_role');
    }
}
