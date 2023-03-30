<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportBotswanaRecordContraArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_botswana_record_contra_archives', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->char('RecordIdentifier', 2)->nullable();
            $table->char('UserBranch', 6)->nullable();
            $table->char('UserCode', 4)->nullable();
            $table->char('SequenceNumber', 6)->nullable();
            $table->char('HomingBranch', 6)->nullable();
            $table->char('HomingAccountNumber', 20)->nullable();
            $table->char('AccountType', 1)->nullable();
            $table->char('Amount', 11)->nullable();
            $table->date('ActionDate')->nullable();
            $table->char('EntryType', 2)->nullable();
            $table->string('UserAbbreviatedName', 20)->nullable();
            $table->string('UserReference', 10)->nullable();
            $table->string('AccountName', 30)->nullable();

            $table->date('ImportDate')->nullable();
            $table->date('ExportDate')->nullable();
            $table->string('Guid', 10)->nullable();
            
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
        Schema::dropIfExists('file_import_botswana_record_contra_archives');
    }
}
