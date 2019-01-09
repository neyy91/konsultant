<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Question;

class CreateQuestionsTable extends Migration
{
    const TABLE = 'questions';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_law_id');
            $table->tinyInteger('from')->default(Question::FROM_DEFAULT)->index();
            $table->string('title', 255)->index();
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('city_id');
            $table->tinyInteger('status')->default(Question::STATUS_UNPUBLISHED)->index();
            $table->boolean('sticky')->default(false)->index();
            $table->timestamp('expertise_date')->nullable()->index();
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->foreign('category_law_id')->references('id')->on(CreateCategoryLawsTable::TABLE);
            $table->foreign('city_id')->references('id')->on(CreateCitiesRegionsTables::TABLE_CITIES);
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
