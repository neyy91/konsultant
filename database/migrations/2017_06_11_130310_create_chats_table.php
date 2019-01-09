<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    const TABLE = 'chats';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->timestamps();
            $table->timestamp('viewed_at')->nullable()->index();
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->string('is', 15)->index()->default(\App\Models\Chat::IS_MESSAGE);
            $table->text('message')->nullable();

            $table->index(['updated_at']);
            $table->index(['created_at']);
            $table->index(['from_id', 'to_id']);

            $table->foreign('from_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('cascade');
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
