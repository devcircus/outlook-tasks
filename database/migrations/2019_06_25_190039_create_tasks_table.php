<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('title')->index();
            $table->dateTime('due_date')->nullable()->index();
            $table->longText('description');
            $table->string('report_to')->nullable();
            $table->boolean('complete')->default(0)->index();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
