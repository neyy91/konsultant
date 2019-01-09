<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesRegionsTables extends Migration
{
    const TABLE_CITIES = 'cities';
    const TABLE_REGIONS = 'regions';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_REGIONS, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique();
        });

        Schema::create(self::TABLE_CITIES, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->index();
            $table->string('slug', 100)->nullable()->index();
            $table->tinyInteger('status')->default(1)->index();
            $table->integer('sort')->default(0)->index();
            $table->text('description')->nullable();
            $table->text('text')->nullable();
            $table->unsignedInteger('region_id')->nullable();

            $table->foreign('region_id')->references('id')->on(self::TABLE_REGIONS)->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLE_CITIES);
        Schema::drop(self::TABLE_REGIONS);
    }
}
