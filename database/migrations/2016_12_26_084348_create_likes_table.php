<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    const TABLE = 'likes';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->morphs('entity');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('rating')->default(0)->index();
            $table->string('text', 255)->nullable();

            $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('cascade');

            $table->index(['created_at']);
            $table->index(['updated_at']);
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
