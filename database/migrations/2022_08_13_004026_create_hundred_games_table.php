<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHundredGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hundred_games', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('no_of_win_numbers')->nullable();
            $table->string('win_numbers');
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
        Schema::dropIfExists('hundred_games');
    }
}
