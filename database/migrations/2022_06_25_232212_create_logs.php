<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('message', 255);
            $table->unsignedBigInteger('user_changed_id');
            $table->unsignedBigInteger('user_modified_id');
            $table->unsignedBigInteger('action_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table
                ->foreign('user_changed_id')
                ->references('id')
                ->on('users');

            $table
                ->foreign('user_modified_id')
                ->references('id')
                ->on('users');

            $table
                ->foreign('action_id')
                ->references('id')
                ->on('actions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
