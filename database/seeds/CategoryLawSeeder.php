<?php

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

use App\Models\CategoryLaw;

class CategoryLawSeeder extends Seeder
{
    /**
     * Получение полей
     * @return array
     */
    protected function getFields($name, $faker)
    {
        return [
            'name' => $name,
            'slug' => App\Helper::slug($name, [$faker->numberBetween(1, 100)]),
            'status' => CategoryLaw::PUBLISHED,
            'description' => $faker->realText(300),
            'text' => $faker->realText(3000),
            'from' => $faker->randomElement(CategoryLaw::getFromKeys()),
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = trans('categories');
        $faker = FakerFactory::create('ru_RU');
        foreach ($categories as $catNum => $category) {
            $fields = $this->getFields(is_array($category) ? $category['name'] : $category, $faker);
            $fields['parent_id'] = null;
            $fields['sort'] = $catNum * 10;
            $categoryLaw = CategoryLaw::create($fields);
            if (is_array($category)) {
                foreach ($category['items'] as $subcatNum => $subcategory) {
                    $fields = $this->getFields($subcategory, $faker);
                    $fields['parent_id'] = $categoryLaw->id;
                    $fields['sort'] = $subcatNum * 10;
                    CategoryLaw::create($fields);
                }
            }
        }
    }
}
