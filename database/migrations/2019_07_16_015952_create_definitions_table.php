<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('definitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('definition_type', ['from', 'subject', 'body']);
            $table->enum('rule_type', ['words', 'exact', 'regex']);
            $table->longText('definition');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('definitions');
    }
}
