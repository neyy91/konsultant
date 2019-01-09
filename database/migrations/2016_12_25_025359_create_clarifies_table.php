<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClarifiesTable extends Migration
{
    /**
     * @var string
     */
    const TABLE = 'clarifies';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('to');
            $table->text('text');
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['updated_at']);
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
