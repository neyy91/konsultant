<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{

    const TABLE = 'documents';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_type_id');
            $table->string('title', 255)->index();
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('city_id');
            $table->tinyInteger('status')->default(0)->index();
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->foreign('document_type_id')->references('id')->on(CreateDocumentTypesTable::TABLE);
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
