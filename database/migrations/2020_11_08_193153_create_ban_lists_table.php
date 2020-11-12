<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ban_lists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreignId('post_id')
            ->references('id')
            ->on('posts')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ban_lists');
    }
}
