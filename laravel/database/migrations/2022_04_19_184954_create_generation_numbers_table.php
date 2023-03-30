<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerationNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generation_numbers', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->string('generation_number_botswana', 4,)->default('0000');
            $table->string('generation_number_capitec', 4)->default('0000');
            $table->string('bank', 15)->nullable();
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
        Schema::dropIfExists('generation_numbers');
    }
}
