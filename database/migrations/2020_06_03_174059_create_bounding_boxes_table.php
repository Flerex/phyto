<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoundingBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bounding_boxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('left');
            $table->unsignedInteger('top');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_assignment_id');
            $table->unsignedBigInteger('taggable_id')->nullable()->default(null);
            $table->string('taggable_type')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('task_assignment_id')->references('id')->on('task_assignments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bounding_boxes');
    }
}
