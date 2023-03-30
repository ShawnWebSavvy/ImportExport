<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_fields', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();

            $table->string('field_1', 30)->nullable();
            $table->string('field_2', 30)->nullable();
            $table->string('field_3', 30)->nullable();
            $table->string('field_4', 30)->nullable();
            $table->string('field_5', 30)->nullable();
            $table->string('field_6', 30)->nullable();

            $table->date('dateField_1')->nullable();
            $table->date('dateField_2')->nullable();
            $table->date('dateField_3')->nullable();
            $table->date('dateField_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_fields');
    }
}
