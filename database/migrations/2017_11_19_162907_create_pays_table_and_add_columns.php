<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Pay;
use App\Models\Question;

class CreatePaysTableAndAddColumns extends Migration
{
    const TABLE = 'pays';

    private $tables = [
        CreateQuestionsTable::TABLE,
        CreateCallsTable::TABLE,
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('status')->default(Pay::STATUS_DEFAULT)->index();
            $table->timestamps();
            $table->integer('cost')->nullable()->index()->default(null);
            $table->morphs('service');
            $table->unsignedInteger('user_id');

            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('cascade');
        });

        // Стоимость для ответа
        if (Schema::hasTable(CreateAnswersTable::TABLE)) {
            Schema::table(CreateAnswersTable::TABLE, function(Blueprint $table) {
                $table->integer('cost')->nullable()->index();
            });
        }
        /**
         * Стоимость для документа.
         */
        if (Schema::hasTable(CreateDocumentsTable::TABLE)) {
            Schema::table(CreateDocumentsTable::TABLE, function(Blueprint $table) {
                $table->integer('cost')->nullable()->index();
            });
        }

        // Question
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function(Blueprint $table) {
                    $table->string('pay', 10)->default(Question::PAY_DEFAULT);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function(Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'pay')) {
                        $table->dropColumn('pay');
                    }
                });
            }
        }

        if (Schema::hasTable(CreateAnswersTable::TABLE)) {
            Schema::table(CreateAnswersTable::TABLE, function(Blueprint $table) {
                if (Schema::hasColumn(CreateAnswersTable::TABLE, 'cost')) {
                    $table->dropColumn('cost');
                }
            });
        }

        if (Schema::hasTable(self::TABLE)) {
            Schema::drop(self::TABLE);
        }
    }
}
