<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitBase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("Message", function (Blueprint $table) {
            $table->increments("ID");
            $table->string("Name");

            $table->enum('Type', ['text', 'image', 'system'])->default('text');
            $table->text("Message")->nullable();
            $table->string("Attachment")->nullable();

            $table->dateTime('CreatedDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists("Message");
    }
}
