<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercantileNedbankTransactionsArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercantile_nedbank_transactions_archives', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            
            $table->char('RecordIdentifier', 2)->nullable();
            $table->string('PaymentReference', 35)->nullable();
            $table->char('Amount', 12)->nullable();
            $table->date('ActionDate')->nullable();

            $table->string('TransactionUniqueID', 30)->nullable();
            $table->string('StatementReference', 20)->nullable();
            $table->date('CycleDate')->nullable();
            $table->char('TransactionType', 4)->nullable();
            $table->char('TransactionOrder', 10)->nullable();
            
            $table->char('ServiceType', 2)->nullable();
            $table->string('OriginalPaymentReference', 35)->nullable();
            $table->char('EntryClass', 2)->nullable();
            $table->string('NominatedAccountReference', 35)->nullable();
            $table->char('BDF_Indicator', 1)->nullable();

            $table->string('policy_id', 20);
            //$table->foreign('policy_id')->references('PolicyNumber')->on('mercantile_user_policies')->onDelete('cascade');

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
        Schema::dropIfExists('mercantile_nedbank_transactions_archives');
    }
}
