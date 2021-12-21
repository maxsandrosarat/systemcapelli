<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained()->cascadeOnDelete();
            $table->float('preco_antigo');
            $table->float('preco_novo');
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fim')->nullable();
            $table->string('criou')->nullable();
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
        Schema::dropIfExists('promocaos');
    }
}
