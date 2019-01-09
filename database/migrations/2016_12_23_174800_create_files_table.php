<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    const TABLE = 'files';

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
            $table->string('basename', 255)->index();
            $table->string('dirname', 255)->index();
            $table->string('mime_type', 255)->index();
            $table->bigInteger('size')->index();
            $table->morphs('owner');
            $table->nullableMorphs('parent');
            $table->string('field', 30)->index();
            $table->unsignedInteger('user_id');

            $table->index(['created_at']);
            $table->index(['updated_at']);
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
