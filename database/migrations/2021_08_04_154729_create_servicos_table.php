<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->float('preco');
            $table->time('tempo');
            $table->foreignId('funcao_id')->constrained()->cascadeOnDelete();
            $table->boolean('ativo')->default(true);
            $table->boolean('promocao')->default(false);
            $table->float('preco_antigo')->nullable();
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fim')->nullable();
            $table->string('ressalva')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicos');
    }
}
