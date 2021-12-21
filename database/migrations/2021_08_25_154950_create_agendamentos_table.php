<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained()->cascadeOnDelete();
            $table->foreignId('func_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('data')->nullable();
            $table->time('hora', $precision = 0);
            $table->string('criou')->nullable();
            $table->string('atendeu')->nullable();
            $table->string('cancelou')->nullable();
            $table->string('editou')->nullable();
            $table->string('observacao')->nullable();
            $table->float('valor');
            $table->enum('status',['PENDENTE','ATENDIDO','CANCELADO']);
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
        Schema::dropIfExists('agendamentos');
    }
}
