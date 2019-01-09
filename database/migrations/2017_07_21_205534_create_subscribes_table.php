<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribesTable extends Migration
{
    const TABLE = 'subscribes';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->index();
            $table->timestamp('viewed_at')->nullable()->index();
            $table->morphs('owner');
            $table->unsignedInteger('user_id');

            $table->unique(['owner_type', 'owner_id', 'user_id']);
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
        Schema::dropIfExists(self::TABLE);
    }
}
