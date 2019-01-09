<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationsTable extends Migration
{
    const TABLE = 'educations';

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
            $table->unsignedInteger('lawyer_id');
            $table->boolean('checked')->default(false);
            $table->string('country', 30)->index();
            $table->string('city', 50)->index();
            $table->string('university', 255)->index();
            $table->string('faculty', 255)->index();
            $table->integer('year')->index();

            // TODO: add foreign for lawyer
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
