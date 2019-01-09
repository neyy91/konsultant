<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration
{
    const TABLE = 'calls';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->index();
            $table->string('slug', 100)->nullable();
            $table->tinyInteger('status')->default(0)->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('city_id');
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['updated_at']);
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
