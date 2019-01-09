<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    const TABLE = 'themes';
    const TABLE_LINKS = 'question_theme';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_law_id')->nullable();
            $table->timestamps();
            $table->string('name', 255)->index();
            $table->string('slug', 100)->nullable()->index();
            $table->integer('sort')->default(0)->index();
            $table->tinyInteger('status')->default(1)->index();
            $table->text('description')->nullable();
            $table->text('text')->nullable();

            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->foreign('category_law_id')->references('id')->on(CreateCategoryLawsTable::TABLE);
        });
        Schema::create(self::TABLE_LINKS, function (Blueprint $table) {
            $table->unsignedInteger('theme_id');
            $table->unsignedInteger('question_id');

            $table->unique(['theme_id', 'question_id']);
            $table->foreign('theme_id')->references('id')->on(self::TABLE)->onDelete('cascade');
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
        Schema::drop(self::TABLE_LINKS);
        Schema::drop(self::TABLE);
    }
}
