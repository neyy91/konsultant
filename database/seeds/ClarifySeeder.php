<?php

use Illuminate\Database\Seeder;

use App\Models\Clarify;

class ClarifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Clarify::class, 'to_questions', 10)->create();
        factory(Clarify::class, 'to_documents', 10)->create();
        factory(Clarify::class, 'to_calls', 10)->create();
    }
}
