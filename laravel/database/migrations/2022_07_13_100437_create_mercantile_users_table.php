<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercantileUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercantile_users', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            
            $table->string('AccountHolderFullName', 30)->nullable();
            $table->string('AccountHolderSurame', 25)->nullable();
            $table->string('AccountHolderInitials', 25)->nullable();

            $table->char('ClientType', 2)->nullable();
            
            $table->string('policy_id', 20)->nullable()->unique();;
            $table->foreign('policy_id')->references('PolicyNumber')->on('mercantile_user_policies')->onDelete('cascade');

            
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
        Schema::dropIfExists('mercantile_users');
    }
}
