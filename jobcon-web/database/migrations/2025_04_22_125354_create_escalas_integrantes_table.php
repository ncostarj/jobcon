<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscalasIntegrantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalas_integrantes', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->foreignUuid('escala_id')->constrained('escalas', 'id');
			$table->foreignUuid('user_id')->nullable()->constrained('users', 'id');
			$table->string('nome');
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
        Schema::dropIfExists('escalas_integrantes');
    }
}
