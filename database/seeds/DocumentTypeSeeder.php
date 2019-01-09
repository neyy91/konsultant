<?php

use Illuminate\Database\Seeder;

use App\Models\DocumentType;


class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DocumentType::class, 'level1', 5)->create();
        factory(DocumentType::class, 'level2', 10)->create();
    }
}
