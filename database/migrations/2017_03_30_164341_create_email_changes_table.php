<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailChangesTable extends Migration
{
    const TABLE = 'email_changes';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->index();
            
            $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('cascade');
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
