<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    const TABLE = 'pages';

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
            $table->boolean('status')->default(true)->index();
            $table->string('title', 100)->index();
            $table->string('slug', 100)->index();
            $table->text('description')->nullable();
            $table->text('text')->default('');
            $table->string('layout', 15)->nullable();
            $table->string('page_layout', 15)->nullable();
            $table->unsignedInteger('user_id');

            $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLE);
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
