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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('status_user_id');
            $table->unsignedBigInteger('role_id');

            $table->string('hash_id', 100)->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('whatsapp', 11)->nullable();

            $table
                ->foreign('status_user_id')
                ->references('id')
                ->on('status_user');

            $table
                ->foreign('role_id')
                ->references('id')
                ->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
