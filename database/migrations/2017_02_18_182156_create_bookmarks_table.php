<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksTable extends Migration
{
    const TABLE = 'bookmarks';
    const TABLE_CATEGORY = 'bookmark_categories';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_CATEGORY, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->unsignedInteger('lawyer_id')->nullable();
            $table->foreign('lawyer_id')->references('id')->on(CreateLawyersTable::TABLE)->onDelete('cascade');
        });
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('category_id');
            $table->timestamps();
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('lawyer_id');

            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->unique(['question_id', 'lawyer_id']);
            $table->foreign('lawyer_id')->references('id')->on(CreateLawyersTable::TABLE)->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on(self::TABLE_CATEGORY)->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on(CreateQuestionsTable::TABLE)->onDelete('cascade');
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
        Schema::drop(self::TABLE_CATEGORY);
    }
}
