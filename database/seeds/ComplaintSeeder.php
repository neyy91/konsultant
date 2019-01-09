<?php

use Illuminate\Database\Seeder;

use App\Models\Complaint;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Complaint::class, 'to_questions', 5)->create();
        factory(Complaint::class, 'to_documents', 5)->create();
        factory(Complaint::class, 'to_calls', 5)->create();
        factory(Complaint::class, 'to_answers', 10)->create();
    }
}
