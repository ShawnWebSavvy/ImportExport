<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportBotswanaArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_botswana_archives', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            
            $table->string('RecipientAccountHolderName', 25)->nullable();
            $table->string('RecipientAccountHolderSurname', 25)->nullable();
            $table->string('RecipientAccountHolderInitials', 6)->nullable();
            $table->string('RecipientAccountHolderAbbreviatedName', 15)->nullable();
            $table->char('RecipientID', 13)->nullable();

            $table->char('BranchCode', 6)->nullable();
            $table->char('RecipientAccountNumber', 20)->nullable();
            $table->char('RecipientNonStandardAccountNumber', 20)->nullable();
            $table->char('RecipientAccountType', 1)->nullable();
            $table->string('AccountReference', 30)->nullable();
            $table->decimal('RecipientAmount', 10, 2)->nullable(); 

            $table->string('PolicyNumber', 30)->nullable();
        
            $table->string('Guid', 20)->nullable();
            $table->date('ActionDate')->nullable();
            $table->date('ImportDate')->nullable();
            $table->date('ExportDate')->nullable();
            
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
        Schema::dropIfExists('file_import_botswana_archives');
    }
}
