<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->index();
            $table->string('name')->index();
            $table->softDeletes();
            $table->timestamps();
        });

        // Insert default categories
        // $category = 'none';
        // $slug = Str::slug($category);

        // DB::table('categories')->insert(
        //     [
        //         'name' => $category,
        //         'slug' => $slug,
        //         'created_at' => now(),
        //     ]
        // );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
