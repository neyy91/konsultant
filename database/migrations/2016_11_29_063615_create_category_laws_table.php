<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryLawsTable extends Migration
{
    const TABLE = 'category_laws';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();
            $table->string('name', 255)->index();
            $table->string('slug', 100)->nullable()->index();
            $table->tinyInteger('from')->index()->default(1);
            $table->integer('sort')->default(0)->index();
            $table->tinyInteger('status')->default(1)->index();
            $table->text('description')->nullable();
            $table->text('text')->nullable();

            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->foreign('parent_id')->references('id')->on(self::TABLE);
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
