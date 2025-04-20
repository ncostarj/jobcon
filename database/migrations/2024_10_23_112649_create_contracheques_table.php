<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContrachequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracheques', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->foreignUuid('user_id')->constrained('users', 'id');
			$table->foreignUuid('empresa_id')->constrained('empresas', 'id');
			$table->date('competencia');
			$table->string('tipo');
			$table->decimal('salario_base');
			$table->decimal('salario_liquido');
			$table->decimal('total_vencimentos', 8, 2);
			$table->decimal('total_descontos', 8, 2);
			$table->string('comprovante')->nullable();
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
        Schema::dropIfExists('contracheques');
    }
}
