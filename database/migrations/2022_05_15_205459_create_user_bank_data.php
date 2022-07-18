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
        Schema::create('user_bank_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('agency', 3)->nullable();
            $table->string('agency_digit', 1)->nullable();
            $table->string('checking_account', 3)->nullable();
            $table->string('checking_account_digit', 1)->nullable();
            $table->string('pix', 150)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('type_transfers_id')->nullable();
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');

            $table
                ->foreign('bank_id')
                ->references('id')
                ->on('banks');

            $table
                ->foreign('type_transfers_id')
                ->references('id')
                ->on('type_transfers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bank_data');
    }
};
