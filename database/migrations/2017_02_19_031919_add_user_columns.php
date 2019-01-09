<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\User;

class AddUserColumns extends Migration
{
    protected $tables = [CreateQuestionsTable::TABLE, CreateDocumentsTable::TABLE, CreateCallsTable::TABLE,  CreateComplaintsTable::TABLE];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(CreateUsersTable::TABLE)) {
            Schema::table(CreateUsersTable::TABLE, function (Blueprint $table) {
                if (Schema::hasColumn(CreateUsersTable::TABLE, 'name')) {
                    $table->dropColumn('name');
                }
                $table->boolean('status')->default(true)->index();
                $table->string('firstname', 255)->index();
                $table->string('lastname', 255)->nullable();
                $table->string('middlename', 255)->nullable();
                $table->string('telephone', 11)->nullable();
                $table->date('birthday')->nullable();
                $table->unsignedInteger('city_id')->nullable();
                $table->string('gender', 10)->nullable();
                $table->string('linebreak', 10)->default(User::LINEBREAK_DEFAULT);
                $table->string('crop', 255)->nullable();
                $table->string('comet_key', 100)->nullable()->index();
                $table->timestamp('last_time')->nullable()->index();
                $table->timestamp('login_at')->nullable()->index();
                $table->timestamp('logout_at')->nullable()->index();

                $table->foreign('city_id')->references('id')->on(CreateCitiesRegionsTables::TABLE_CITIES)->onDelete('set null');
            });
            foreach ($this->tables as $tname) {
                if (Schema::hasTable($tname)) {
                    Schema::table($tname, function (Blueprint $table) {
                        $table->unsignedInteger('user_id')->nullable();

                        $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLE)->onDelete('set null');
                    });
                }
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
        if (Schema::hasTable(CreateUsersTable::TABLE)) {
            Schema::table(CreateUsersTable::TABLE, function (Blueprint $table) {
                $table->dropForeign(['city_id']);
                foreach (['status', 'firstname', 'lastname', 'middlename', 'telephone', 'birthday', 'city_id', 'gender', 'linebreak', 'crop', 'last_time'] as $column) {
                    $table->dropColumn($column);
                }
                $table->string('name');
            });
            foreach ($this->tables as $tname) {
                if (Schema::hasTable($tname)) {
                    Schema::table($tname, function (Blueprint $table) {
                        $table->dropForeign(['user_id']);
                        $table->dropColumn('user_id');
                    });
                }
            }
        }
    }
}
