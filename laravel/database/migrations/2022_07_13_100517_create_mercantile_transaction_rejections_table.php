<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercantileTransactionRejectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercantile_transaction_rejections', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            
            $table->string('policy_id', 20);
            $table->foreign('policy_id')->references('PolicyNumber')->on('mercantile_user_policies')->onDelete('cascade');

            //$table->integer('transaction_id')->length(10)->unsigned()->index()->nullable(false);
            //$table->foreign('transaction_id')->references('id')->on('mercantile_transactions')->onDelete('cascade');

            $table->boolean('Processed', 1)->nullable();

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
        Schema::dropIfExists('mercantile_transaction_rejections');
    }
}
