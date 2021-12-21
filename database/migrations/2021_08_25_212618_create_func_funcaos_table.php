<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncFuncaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('func_funcaos', function (Blueprint $table) {
            $table->foreignId('func_id')->constrained()->cascadeOnDelete();
            $table->foreignId('funcao_id')->constrained()->cascadeOnDelete();
            $table->primary(['func_id','funcao_id']);
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
        Schema::dropIfExists('func_funcaos');
    }
}
