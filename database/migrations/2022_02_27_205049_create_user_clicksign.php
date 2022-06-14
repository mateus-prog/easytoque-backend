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
        Schema::create('user_clicksign', function (Blueprint $table) {
            $table->id();
            $table->string('signatario_key', 45)->nullable();
            $table->string('document_key', 45)->nullable();
            $table->string('request_signature_key', 45)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_clicksign');
    }
};
