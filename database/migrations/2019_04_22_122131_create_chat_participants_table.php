<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('chat_room_id')->unsigned();
            $table->string('name')->nullable();
            $table->boolean('get_push_alarm')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms');

            $table->unique(['user_id', 'chat_room_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_participants');
    }
}
