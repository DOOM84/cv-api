<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('ru_title');
            $table->string('en_title');
            $table->string('ua_title');
            $table->string('ru_subtitle');
            $table->string('en_subtitle');
            $table->string('ua_subtitle');
            $table->string('slug');
            $table->text('body');
            $table->string('source')->nullable();
            $table->string('author')->default('admin');
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        \DB::statement('ALTER TABLE posts ADD FULLTEXT fulltext_index (ru_title, en_title, ua_title, ru_subtitle, en_subtitle, ua_subtitle, body)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
