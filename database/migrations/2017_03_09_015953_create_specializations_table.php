<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecializationsTable extends Migration
{
    const TABLE = 'specializations';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->unsignedInteger('lawyer_id');
            $table->unsignedInteger('category_law_id');

            $table->unique(['lawyer_id', 'category_law_id']);
            $table->foreign('lawyer_id')->references('id')->on(CreateLawyersTable::TABLE)->onDelete('cascade');
            $table->foreign('category_law_id')->references('id')->on(CreateCategoryLawsTable::TABLE)->onDelete('cascade');
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
