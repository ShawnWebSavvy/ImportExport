<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportBotswanaRecordUserHeaderArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_botswana_record_user_header_archives', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->char('RecordIdentifier', 2)->nullable();
            $table->char('UserCode', 4)->nullable();
            $table->date('CreationDate')->nullable();
            $table->date('PurgeDate')->nullable();
            $table->date('ActionDateFirst')->nullable();
            $table->date('ActionDateLast')->nullable();
            $table->char('SequenceNumber', 6)->nullable();
            $table->char('GenerationNumber', 4)->nullable();
            $table->string('Service', 10)->nullable();

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
        Schema::dropIfExists('file_import_botswana_record_user_header_archives');
    }
}
