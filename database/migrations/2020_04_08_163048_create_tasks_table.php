<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tl_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key.
            $table->uuid('task_list_id');
            $table->foreign('task_list_id')->references('id')->on('tl_task_lists');

            $table->text('description');
            $table->dateTime('due_date');
            $table->integer('status',false,true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tl_tasks');
    }
}
