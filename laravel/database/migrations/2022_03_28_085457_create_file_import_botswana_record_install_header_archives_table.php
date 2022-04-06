<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportBotswanaRecordInstallHeaderArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_botswana_record_install_header_archives', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->char('RecordIdentifier', 2)->nullable();
            $table->char('VolumeNumber', 4)->nullable();
            $table->string('TapeSerialNumber', 8)->nullable();
            $table->char('InstallationIDfrom', 4)->nullable();
            $table->char('InstallationIDto', 4)->nullable();
            $table->date('CreationDate')->nullable();
            $table->date('PurgeDate')->nullable();
            $table->char('GenerationNumber', 4)->nullable();
            $table->char('BlockLength', 4)->nullable();
            $table->char('RecordLength', 4)->nullable();
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
        Schema::dropIfExists('file_import_botswana_record_install_header_archives');
    }
}
