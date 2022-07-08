<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportCapitecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_capitecs', function (Blueprint $table) {
            $table->string('AccountHolderFullName', 30)->nullable();
            $table->string('AccountHolderSurame', 20)->nullable();
            $table->string('AccountHolderInitials', 6)->nullable();

            $table->char('ClientsAccountNumber', 20)->nullable();
            $table->char('ClientsBranchCode', 6)->nullable();
            $table->char('DestinationAccountNumber', 20)->nullable();
            $table->char('DestinationBranchCode', 6)->nullable();

            $table->string('PaymentReference', 35)->nullable();
            $table->char('Amount', 6)->nullable();
            $table->date('ActionDate')->nullable();

            $table->string('TransactionUniqueID', 30)->nullable();
            $table->string('StatementReference', 20)->nullable();
            $table->string('ContractReference', 20)->nullable();
            $table->date('CycleDate')->nullable();

            $table->char('TransactionType', 4)->nullable();
            $table->char('ClientType', 2)->nullable();
            $table->char('ChargesAccountNumber', 20)->nullable();
            $table->char('ServiceType', 2)->nullable();
            $table->string('OriginalPaymentReference', 35)->nullable();
            $table->char('EntryClass', 2)->nullable();
            $table->string('NominatedAccountReference', 35)->nullable();
            $table->char('BDF_Indicator', 1)->nullable();

            $table->string('Guid', 20)->nullable();
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
        Schema::dropIfExists('file_import_capitecs');
    }
}
