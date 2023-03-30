<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_infos', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->string('policy_id', 20)->nullable();
            
            $table->string('AccountHolderFullName', 30)->nullable();
            $table->string('AccountHolderSurame', 25)->nullable();
            $table->string('AccountHolderInitials', 25)->nullable();
            $table->char('ClientType', 2)->nullable();

            $table->char('UserAccountNumber', 20)->nullable();
            $table->char('UserBranchCode', 6)->nullable();
            $table->string('UserBankType', 20)->nullable();

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
        Schema::dropIfExists('customer_infos');
    }
}
