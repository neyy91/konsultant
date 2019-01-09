<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertisesTable extends Migration
{
    const TABLE = 'expertises';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('lawyer_id');
            $table->text('message')->nullable();
            $table->string('type', 15)->index();
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->foreign('question_id')->references('id')->on(CreateQuestionsTable::TABLE)->onDelete('cascade');
            $table->foreign('lawyer_id')->references('id')->on(CreateLawyersTable::TABLE)->onDelete('cascade');
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
