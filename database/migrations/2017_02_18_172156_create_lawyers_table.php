<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawyersTable extends Migration
{
    const TABLE = 'lawyers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            // $table->timestamps();
            $table->tinyInteger('status')->nullable()->index();
            $table->string('contactphones', 255)->nullable();
            $table->string('contactemail', 255)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('site', 255)->nullable();
            $table->string('skype', 30)->nullable();
            $table->boolean('callavailable')->default(true)->index();
            $table->string('timezone', 3)->default('+3')->index();
            $table->boolean('weekdays')->default(true)->index();
            $table->decimal('weekdaysfrom', 4, 2)->default(10.00)->index();
            $table->decimal('weekdaysto', 4, 2)->default(19.00)->index();
            $table->boolean('weekend')->default(false)->index();
            $table->decimal('weekendfrom', 4, 2)->default(10.00)->index();
            $table->decimal('weekendto', 4, 2)->default(19.00)->index();
            $table->string('companyname', 255)->nullable()->index();
            $table->string('position', 100)->nullable();
            $table->tinyInteger('experience')->default(0)->index();
            $table->integer('costcall')->nullable()->index();
            $table->integer('costchat')->nullable()->index();
            $table->integer('costdocument')->nullable()->index();
            $table->text('cost')->nullable();
            $table->string('aboutmyself', 150)->nullable();
            $table->boolean('companyowner')->default(false)->index();
            $table->unsignedInteger('company_id')->nullable();
            $table->boolean('expert')->default(true)->index();
            $table->decimal('rating', 2, 1)->default(0)->index();

            $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on(CreateCompaniesTable::TABLE)->onDelete('set null');
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
