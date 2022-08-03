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
        Schema::create('user_corporate', function (Blueprint $table) {
            $table->id();
            $table->string('corporate_name', 50);
            $table->string('cnpj', 18);
            $table->string('address', 40);
            $table->string('number', 10);
            $table->string('complement', 10)->nullable();
            $table->string('district', 40);
            $table->string('city', 40);
            $table->string('cep', 9);
            $table->string('url_logo', 255)->nullable();
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');

            $table
                ->foreign('state_id')
                ->references('id')
                ->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_address');
    }
};
