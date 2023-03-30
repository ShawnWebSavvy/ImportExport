<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapitecAccounts1000sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capitec_accounts1000s', function (Blueprint $table) {
            $table->increments('id')->length(10);
            $table->string('PolicyNumber', 20)->nullable()->unique();
            $table->boolean('dummy_data_Capitec_active', 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capitec_accounts1000s');
    }
}
