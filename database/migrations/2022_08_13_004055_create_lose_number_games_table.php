<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoseNumberGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lose_number_games', function (Blueprint $table) {
            $table->id();
            $table->string('lose_number');
            $table->time('timer')->nullable(); // hours
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('lose_number_games');
    }
}
