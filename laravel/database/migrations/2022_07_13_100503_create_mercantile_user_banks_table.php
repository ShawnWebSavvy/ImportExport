<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercantileUserBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercantile_user_banks', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            
            $table->char('UserAccountNumber', 20)->nullable();
            $table->char('UserBranchCode', 6)->nullable();
            $table->string('UserBankType', 20)->nullable();
            
            $table->string('policy_id', 20);
            $table->foreign('policy_id')->references('PolicyNumber')->on('mercantile_user_policies')->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercantile_user_banks');
    }
}
