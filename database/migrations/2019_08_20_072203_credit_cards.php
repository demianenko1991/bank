<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreditCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->char('number', 16);
            $table->date('expires_at');
            $table->char('cvv', 3);
            $table->decimal('balance', 12, 2)->default(0);
            $table->boolean('state')
                ->default(1)
                ->comment('0 - blocked, 1 - active');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->on('users')
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
        Schema::table('user_cards', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::drop('user_cards');
    }
}
