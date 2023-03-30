<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercantileUserPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercantile_user_policies', function (Blueprint $table) {
            
            $table->increments('id')->length(10);
            
            $table->string('policyNumber', 20)->nullable()->unique();
            $table->string('row', 500)->nullable();
            $table->boolean('active', 1)->nullable();
            $table->boolean('dummy_data_Capitec_active', 1)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            
/*
            $table->increments('id')->length(10);
            
            $table->string('PolicyNumber', 20)->nullable()->unique();

            $table->boolean('dummy_data_Capitec_active', 1)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
*/            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercantile_user_policies');
    }
}
