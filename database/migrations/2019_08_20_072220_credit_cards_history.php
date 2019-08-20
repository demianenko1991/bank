<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreditCardsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('message');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('card_id')->nullable();
            $table->foreign('card_id')
                ->on('user_cards')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
        });
        Schema::drop('user_transactions');
    }
}
