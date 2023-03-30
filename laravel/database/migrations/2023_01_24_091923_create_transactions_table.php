<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id')->length(10);
            

            $table->string('policy_id', 20)->nullable();
            $table->string('transaction', 500)->nullable();
            $table->string('UserBankType', 10)->nullable();
            $table->string('isCapitecControl', 5);
            $table->char('TransactionType', 4);
            $table->char('Amount', 15);
            $table->char('AccountNumber', 50);

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
        Schema::dropIfExists('transactions');
    }
}
