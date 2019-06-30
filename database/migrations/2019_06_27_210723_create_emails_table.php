<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->index();
            $table->string('outlook_id')->index();
            $table->string('subject')->nullable()->index();
            $table->string('from_address')->index();
            $table->string('from_name')->index();
            $table->longText('body')->nullable();
            $table->dateTime('received_at')->index();
            $table->boolean('assigned')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
