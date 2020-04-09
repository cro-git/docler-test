<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tl_task_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key.
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('tl_users');

            $table->char('name',255);

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
        Schema::dropIfExists('tl_task_lists');
    }
}
