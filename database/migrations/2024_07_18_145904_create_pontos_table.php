<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePontosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pontos', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->foreignUuid('user_id')->constrained('users', 'id');
			$table->date('dia')->useCurrent();
			$table->string('categoria', 12);
			$table->boolean('pedir_ajuste')->default(false);
			$table->boolean('ajuste_finalizado')->default(false);
			$table->string('observacao')->nullable();
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
        Schema::dropIfExists('pontos');
    }
}
