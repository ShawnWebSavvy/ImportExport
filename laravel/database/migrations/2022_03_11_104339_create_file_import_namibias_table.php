<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportNamibiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_namibias', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->string('ContractNumber', 15)->nullable();
            $table->string('ReferenceNumber', 30)->nullable();
            $table->string('RecipientAccountHolderName', 25)->nullable();
            $table->string('RecipientAccountHolderSurname', 25)->nullable();
            $table->string('RecipientAccountHolderInitials', 6)->nullable();
            $table->string('RecipientAccountHolderAbbreviatedName', 15)->nullable();
            $table->string('OrganizatonName', 25)->nullable();
            $table->string('OrganizationCode', 8)->nullable();
            $table->char('BranchCode', 6)->nullable();
            $table->string('BranchSwiftBicCode', 11)->nullable();
            $table->char('RecipientAccountNumber', 20)->nullable();
            $table->char('RecipientNonStandardAccountNumber', 20)->nullable();
            $table->char('RecipientAccountType', 1)->nullable();
            $table->decimal('RecipientAmount', 10, 2)->nullable(); 
            $table->date('ActionDate')->nullable();
            $table->char('EntryType', 2)->nullable();
            $table->string('TransactionType', 2)->nullable();
            $table->char('ServiceType', 10)->nullable();
            $table->char('Tracking', 2)->nullable();
            $table->char('SequenceNumber', 10)->nullable();
            $table->string('SettlementReferenceTraceCode', 6)->nullable();
            $table->string('ContractReference', 25)->nullable();
            $table->string('CollectionReason', 25)->nullable();

            $table->date('ImportDate')->nullable();
            $table->string('Guid', 10)->nullable();
            
            $table->softDeletes();
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
        Schema::dropIfExists('file_import_namibias');
    }
}
