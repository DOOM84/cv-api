<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('ru_name');
            $table->string('en_name');
            $table->string('ua_name');
            $table->string('slug');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('category_post', function (Blueprint $table) {
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('post_id')->unsigned();
            //$table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('categories');
    }
}
