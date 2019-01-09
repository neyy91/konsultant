<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UsersTableSeeder::class);
        $this->call(CategoryLawSeeder::class);
        $this->call(CityRegionSeeder::class);
        $this->call(UserDefineSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(LawyerSeeder::class);
        $this->call(EducationSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(ThemeSeeder::class);
        $this->call(DocumentTypeSeeder::class);
        $this->call(DocumentSeeder::class);
        $this->call(CallSeeder::class);
        $this->call(AnswerSeeder::class);
        $this->call(ClarifySeeder::class);
        $this->call(LikeSeeder::class);
        $this->call(FileSeeder::class);
        $this->call(ComplaintSeeder::class);
        $this->call(BookmarkSeeder::class);
        $this->call(SpecializationSeeder::class);
        $this->call(RoleSeeder::class);

        Model::reguard();
    }
}
