<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{

    const TABLE = 'notifications';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->timestamp('created_at');
            $table->unsignedInteger('user_id');
            $table->string('type', 10);

            $table->unique(['user_id', 'type']);
            $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLE);
    }
}
