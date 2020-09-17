<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('ru_name');
            $table->string('en_name');
            $table->string('ua_name');
            $table->string('ru_details');
            $table->string('en_details');
            $table->string('ua_details');
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_skill', function (Blueprint $table) {
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('skill_id')->unsigned();
            //$table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_skill');
        Schema::dropIfExists('projects');
    }
}
