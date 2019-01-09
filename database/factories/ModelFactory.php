<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


/**
 * Пользователи.
 */
$factory->defineAs(App\Models\User::class, 'lawyer', function (Faker\Generator $faker) {
    static $number = 0;
    $number += 1;
    $gender = $faker->boolean(50) ? App\Models\User::GENDER_MALE : App\Models\User::GENDER_FEMALE; 
    return [
        'gender' => $gender,
        'firstname' => $gender == App\Models\User::GENDER_MALE ? $faker->firstNameMale : $faker->firstNameFemale,
        'lastname' => $faker->lastName . ($gender == App\Models\User::GENDER_FEMALE ? 'а' : ''),
        'middlename' => $gender == App\Models\User::GENDER_MALE ? $faker->middleNameMale : $faker->middleNameFemale,
        'birthday' => $faker->dateTimeBetween('-40 years', '-20 years'),
        'city_id' => App\Models\City::setDefault()->inRandomOrder()->first()->id,
        'linebreak' => $faker->randomElement(array_keys(App\Models\User::getLinebreaks())),
        'email' => 'lawyer' . $number . '@konsultant.ru',
        'password' => bcrypt('pw123456!'),
        'remember_token' => str_random(10),
        'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
    ];
});
$factory->defineAs(App\Models\User::class, 'user', function (Faker\Generator $faker) {
    static $number = 0;
    $number += 1;
    $gender = $faker->boolean(50) ? App\Models\User::GENDER_MALE : App\Models\User::GENDER_FEMALE; 
    return [
        'gender' => $gender,
        'firstname' => $gender == App\Models\User::GENDER_MALE ? $faker->firstNameMale : $faker->firstNameFemale,
        'lastname' => $faker->lastName . ($gender == App\Models\User::GENDER_FEMALE ? 'а' : ''),
        'middlename' => $gender == App\Models\User::GENDER_MALE ? $faker->middleNameMale : $faker->middleNameFemale,
        'birthday' => $faker->dateTimeBetween('-40 years', '-20 years'),
        'city_id' => App\Models\City::setDefault()->inRandomOrder()->first()->id,
        'linebreak' => $faker->randomElement(array_keys(App\Models\User::getLinebreaks())),
        'email' => 'user' . $number . '@konsultant.ru',
        'password' => bcrypt('pw123456!'),
        'remember_token' => str_random(10),
        'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
    ];
});
$factory->defineAs(App\Models\User::class, App\Models\Role::ADMIN, function (Faker\Generator $faker) {
    static $number = 0;
    $number += 1;
    $gender = $faker->boolean(50) ? App\Models\User::GENDER_MALE : App\Models\User::GENDER_FEMALE; 
    return [
        'gender' => $gender,
        'firstname' => $gender == App\Models\User::GENDER_MALE ? $faker->firstNameMale : $faker->firstNameFemale,
        'lastname' => $faker->lastName . ($gender == App\Models\User::GENDER_FEMALE ? 'а' : ''),
        'linebreak' => $faker->randomElement(array_keys(App\Models\User::getLinebreaks())),
        'email' => App\Models\Role::ADMIN . $number . '@konsultant.ru',
        'password' => bcrypt('pw123456!'),
        'remember_token' => str_random(10),
        'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
    ];
});

/**
 * Роли.
 */
$factory->defineAs(App\Models\Role::class, App\Models\Role::ADMIN, function(Faker\Generator $faker) {
    static $userIds = null;
    if (!$userIds) {
        $userIds = App\Models\User::where('email', 'like', App\Models\Role::ADMIN . '%')->get()->pluck('id')->toArray();
    }
    return [
        'user_id' => array_pop($userIds),
        'role' => App\Models\Role::ADMIN,
    ];
});


/**
 * Города и области.
 */
// Области
$factory->define(App\Models\Region::class, function (Faker\Generator $faker) {
    $name = $faker->unique()->region . ' ' . $faker->regionSuffix;
    return [
        'name' => $name,
    ];
});
// Города
$factory->define(App\Models\City::class, function (Faker\Generator $faker) {
    $name = $faker->unique()->city;
    $region = App\Models\Region::inRandomOrder()->first();
    return [
        'name' => $name,
        'slug' => App\Helper::slug($name, [$faker->numberBetween(1, 100)]),
        'status' => App\Models\City::PUBLISHED,
        'sort' => $faker->numberBetween(-10, 20) * 100,
        'description' => $faker->realText(500),
        'text' => $faker->realText(3000),
        'region_id' => $region->id,
    ];
});


/**
 * Вопросы.
 */
$factory->define(App\Models\Question::class, function (Faker\Generator $faker) {
    $description = $faker->realText($faker->numberBetween(100, 500));
    $titles = preg_split('#[\,\;\.\?\!]#', $description, 3);
    $title = trim($titles[0]) . '?';
    $categoryLaw = App\Models\CategoryLaw::whereNotNull('parent_id')->inRandomOrder()->first();
    $city = App\Models\City::inRandomOrder()->first();
    $statuses = App\Models\Question::getStatusKeys();
    return [
        'category_law_id' => $categoryLaw->id,
        'from' => $faker->randomElement(App\Models\Question::getFromKeys()),
        'pay' => App\Models\Question::PAY_FREE,
        'title' => $title,
        'slug' => App\Helper::slug($title, [$faker->numberBetween(1, 100)]),
        'description' => $description,
        'city_id' => $city->id,
        'status' => $faker->randomElement($statuses + array_fill(count($statuses), 5, App\Models\Question::STATUS_IN_WORK)),
        'sticky' => $faker->boolean(20),
        'user_id' => App\Models\User::doesntHave('lawyer')->inRandomOrder()->first()->id,
        'created_at' => $faker->dateTimeBetween('-3 years', 'now'),
    ];
});
// Темы для категории права
$factory->define(App\Models\Theme::class, function (Faker\Generator $faker) {
    $question = App\Models\Question::inRandomOrder()->first();
    $words = explode(' ', $question->title);
    $words = array_map(function($value) {
        return Str::lower(trim($value, ' ,.:;-?!«»—'));
    }, $words);
    $count = count($words);
    $length = $faker->numberBetween(1, 3);
    $words = array_slice($words, $faker->numberBetween(0, $count - $length), $length);
    $name = implode(' ', $words);
    $categoryLaw = App\Models\CategoryLaw::whereNotNull('parent_id')->inRandomOrder()->first();
    return [
        'name' => $name,
        'slug' => App\Helper::slug($name, [$faker->numberBetween(1, 100)]),
        'sort' => $faker->numberBetween(-10, 20) * 100,
        'status' => App\Models\Theme::PUBLISHED,
        'description' => $faker->realText(500),
        'text' => $faker->realText(3000),
        'category_law_id' => $categoryLaw->id,
    ];
});
// Уточнения к вопросу
$factory->defineAs(App\Models\Clarify::class, 'to_questions', function (Faker\Generator $faker) {
    $question = App\Models\Question::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $type = $morphMap->search(App\Models\Question::class);
    return [
        'to_type' => $type,
        'to_id' => $question->id,
        'text' => $faker->realText($faker->numberBetween(30, 300)),
    ];
});
// Жалоба на вопрос
$factory->defineAs(App\Models\Complaint::class, 'to_questions', function (Faker\Generator $faker) {
    $question = App\Models\Question::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $againstType = $morphMap->search(App\Models\Question::class);
    return [
        'against_type' => $againstType,
        'against_id' => $question->id,
        'type' => $faker->randomElement(App\Models\Complaint::getTypeKeys()),
        'comment' => $faker->realText($faker->numberBetween(20, 250)),
        'user_id' => App\Models\User::inRandomOrder()->first()->id,
    ];
});
// Ответы на вопрос
$factory->defineAs(App\Models\Answer::class, 'to_questions', function (Faker\Generator $faker) {
    $question = App\Models\Question::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $type = $morphMap->search(App\Models\Question::class);
    return [
        'to_type' => $type,
        'to_id' => $question->id,
        'text' => $faker->realText($faker->numberBetween(100, 500)),
        'is' => $faker->boolean(20),
        'created_at' => $faker->dateTimeBetween($question->created_at, 'now'),
        'from_id' => App\Models\Lawyer::inRandomOrder()->first()->id,
        'from_type' => App\Models\Lawyer::MORPH_NAME,
    ];
});


/**
 * Типы документов.
 */
// уровень 1
$factory->defineAs(App\Models\DocumentType::class, 'level1', function (Faker\Generator $faker) {
    $description = $faker->realText(500);
    $descLen = strlen($description);
    $pos = strpos($description, ' ', $faker->numberBetween(5, $descLen > 50 ? 50 : $descLen));
    $name = trim(str_replace([',', '.', ';','?','!','-','—',':','«','»'], '', substr($description, 0, $pos)));
    // $name = trim($faker->sentence(1), '.,');
    $parent = App\Models\DocumentType::inRandomOrder()->first();
    return [
        'name' => $name,
        'slug' => App\Helper::slug($name, [$faker->numberBetween(1, 100)]),
        'sort' => $faker->numberBetween(-10, 20) * 100,
        'status' => App\Models\DocumentType::PUBLISHED,
        'description' => $description,
        'text' => $faker->realText(3000),
        'parent_id' => null,
    ];
});
// уровень 2
$factory->defineAs(App\Models\DocumentType::class, 'level2', function (Faker\Generator $faker) {
    $description = $faker->realText(500);
    $name = trim(str_replace([',', '.', ';','?','!','-','—',':','«','»'], '', substr($description, 0, strpos($description, ' ', $faker->numberBetween(10, 30)))));
    $parent = App\Models\DocumentType::whereNull('parent_id')->inRandomOrder()->first();
    return [
        'name' => $name,
        'slug' => App\Helper::slug($name, [$faker->numberBetween(1, 100)]),
        'sort' => $faker->numberBetween(-10, 20) * 100,
        'status' => App\Models\DocumentType::PUBLISHED,
        'description' => $description,
        'text' => $faker->realText(3000),
        'parent_id' => $parent->id,
    ];
});


/**
 * Документы.
 */
$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    $description = $faker->realText($faker->numberBetween(100, 500));
    $titles = preg_split('#[\,\;\.\?\!]#', $description, 3);
    $title = trim($titles[0]);
    $documentType = App\Models\DocumentType::inRandomOrder()->first();
    $city = App\Models\City::inRandomOrder()->first();
    $statuses = App\Models\Document::getStatusKeys();
    return [
        'document_type_id' => $documentType->id,
        'cost' => $faker->numberBetween(0, 1000),
        'title' => $title,
        'slug' => App\Helper::slug($title, [$faker->numberBetween(1, 100)]),
        'description' => $description,
        'city_id' => $city->id,
        'status' => $faker->randomElement($statuses + array_fill(count($statuses), 5, App\Models\Document::STATUS_IN_WORK)),
        'created_at' => $faker->dateTimeBetween('-3 years', 'now'),
        'user_id' => App\Models\User::doesntHave('lawyer')->inRandomOrder()->first()->id,
    ];
});
// Ответы для документов.
$factory->defineAs(App\Models\Answer::class, 'to_documents', function (Faker\Generator $faker) {
    $document = App\Models\Document::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $type = $morphMap->search(App\Models\Document::class);
    return [
        'to_type' => $type,
        'to_id' => $document->id,
        'text' => $faker->realText($faker->numberBetween(100, 500)),
        'cost' => $faker->numberBetween(0, 1000),
        'is' => $faker->boolean(20),
        'created_at' => $faker->dateTimeBetween($document->created_at, 'now'),
        'from_id' => App\Models\Lawyer::inRandomOrder()->first()->id,
        'from_type' => App\Models\Lawyer::MORPH_NAME,
    ];
});
// Уточнения к документам.
$factory->defineAs(App\Models\Clarify::class, 'to_documents', function (Faker\Generator $faker) {
    $document = App\Models\Document::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $type = $morphMap->search(App\Models\Document::class);
    return [
        'to_type' => $type,
        'to_id' => $document->id,
        'text' => $faker->realText($faker->numberBetween(30, 300)),
    ];
});
// Жалоба на документ
$factory->defineAs(App\Models\Complaint::class, 'to_documents', function (Faker\Generator $faker) {
    $document = App\Models\Document::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $againstType = $morphMap->search(App\Models\Document::class);
    return [
        'against_type' => $againstType,
        'against_id' => $document->id,
        'type' => $faker->randomElement(App\Models\Complaint::getTypeKeys()),
        'comment' => $faker->realText($faker->numberBetween(20, 250)),
        'user_id' => App\Models\User::inRandomOrder()->first()->id,
    ];
});


/**
 * Ответы и предложения
 */
// Жалоба на ответ
$factory->defineAs(App\Models\Complaint::class, 'to_answers', function (Faker\Generator $faker) {
    $answer = App\Models\Answer::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $againstType = $morphMap->search(App\Models\Answer::class);
    return [
        'against_type' => $againstType,
        'against_id' => $answer->id,
        'type' => $faker->randomElement(App\Models\Complaint::getTypeKeys()),
        'comment' => $faker->realText($faker->numberBetween(20, 250)),
        'user_id' => App\Models\User::inRandomOrder()->doesntHave('lawyer')->first()->id,
    ];
});
// Оценка ответа
$factory->defineAs(App\Models\Like::class, 'to_answers', function (Faker\Generator $faker) {
    $answer = App\Models\Answer::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $type = $morphMap->search(App\Models\Answer::class);
    $id = $faker->randomElement([
        App\Models\User::inRandomOrder()->first()->id,
        App\Models\Lawyer::inRandomOrder()->first()->user->id
    ]);
    return [
        'entity_type' => $type,
        'entity_id' => $answer->id,
        'rating' => $faker->randomElement(App\Models\Like::ratingKeys()),
        'text' => $faker->boolean(20) ? $faker->realText($faker->numberBetween(30, 100)) : null,
        'user_id' => $id,
    ];
});


/**
 * Звонки.
 */
$factory->define(App\Models\Call::class, function (Faker\Generator $faker) {
    $description = $faker->realText($faker->numberBetween(100, 500));
    $titles = preg_split('#[\,\;\.\?\!]#', $description, 3);
    $title = trim($titles[0]);
    $city = App\Models\City::inRandomOrder()->first();
    $statuses = App\Models\Call::getStatusKeys();
    return [
        'title' => $title,
        'pay' => App\Models\Call::PAY_FREE,
        'slug' => App\Helper::slug($title, [$faker->numberBetween(1, 100)]),
        'status' => $faker->randomElement($statuses + array_fill(count($statuses), 5, App\Models\Call::STATUS_IN_WORK)),
        'description' => $faker->boolean(20) ? $description : null,
        'city_id' => $city->id,
        'created_at' => $faker->dateTimeBetween('-3 years', 'now'),
        'user_id' => App\Models\User::inRandomOrder()->doesntHave('lawyer')->inRandomOrder()->first()->id,
    ];
});
// Уточнения к звонку
$factory->defineAs(App\Models\Clarify::class, 'to_calls', function (Faker\Generator $faker) {
    $call = App\Models\Call::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $type = $morphMap->search(App\Models\Call::class);
    return [
        'to_type' => $type,
        'to_id' => $call->id,
        'text' => $faker->realText($faker->numberBetween(30, 300)),
    ];
});
// Жалоба к звонку
$factory->defineAs(App\Models\Complaint::class, 'to_calls', function (Faker\Generator $faker) {
    $call = App\Models\Call::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $againstType = $morphMap->search(App\Models\Call::class);
    return [
        'against_type' => $againstType,
        'against_id' => $call->id,
        'type' => $faker->randomElement(App\Models\Complaint::getTypeKeys()),
        'comment' => $faker->realText($faker->numberBetween(20, 250)),
        'user_id' => App\Models\User::inRandomOrder()->first()->id,
    ];
});
// Ответы к звонку
$factory->defineAs(App\Models\Answer::class, 'to_calls', function (Faker\Generator $faker) {
    $call = App\Models\Call::inRandomOrder()->first();
    $morphMap = collect(Illuminate\Database\Eloquent\Relations\Relation::morphMap());
    $type = $morphMap->search(App\Models\Call::class);
    return [
        'to_type' => $type,
        'to_id' => $call->id,
        'text' => $faker->boolean(10) ? $faker->realText($faker->numberBetween(100, 500)) : null,
        'is' => $call->answers(false)->where('is', true)->count() > 0 ? false : $faker->boolean(50),
        'created_at' => $faker->dateTimeBetween($call->created_at, 'now'),
        'from_id' => App\Models\Lawyer::inRandomOrder()->first()->id,
        'from_type' => App\Models\Lawyer::MORPH_NAME,
    ];
});

/**
 * Закладки.
 */
$factory->define(App\Models\Bookmark::class, function (Faker\Generator $faker) {
    $lawyer = App\Models\Lawyer::inRandomOrder()->first();
    $bms = App\Models\Bookmark::where('lawyer_id', $lawyer->id)->get()->pluck('question_id')->toArray();
    $question = App\Models\Question::published();
    if (!empty($bms) > 0) {
        $question->whereNotIn('id', $bms);
    }
    $question = $question->inRandomOrder()->first();
    $category = App\Models\BookmarkCategory::inRandomOrder()->first();
    if (App\Models\Bookmark::where('lawyer_id', $lawyer->id)->where('question_id', $question->id)->get()->count() > 0  ) {
        return null;
    }
    return [
        'lawyer_id' => $lawyer->id,
        'question_id' => $question->id,
        'category_id' => $category->id,
    ];
});

/**
 * Образование.
 */
$factory->define(App\Models\Education::class, function (Faker\Generator $faker) {
    return [
        'lawyer_id' => App\Models\Lawyer::inRandomOrder()->first()->id,
        'country' => 'Россия',
        'city' => $faker->city,
        'university' => $faker->text(20),
        'faculty' => $faker->text(50),
        'year' => $faker->year,
        'checked' => $faker->boolean(50),
    ];
});

/**
 * Компании.
 */
$factory->define(App\Models\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->realText($faker->numberBetween(50, 250)),
        'status' => $faker->boolean(90),
    ];
});

/**
 * Юристы.
 */
$factory->define(App\Models\Lawyer::class, function (Faker\Generator $faker) {
    static $ids = [];
    static $number = 1;
    if (empty($ids)) {
        $ids = App\Models\User::where('email', 'like', 'lawyer%')->inRandomOrder()->doesntHave('lawyer')->get()->pluck('id')->toArray();
    }
    $companyowner = $faker->boolean(10) ? 1 : 0;
    $company = App\Models\Company::active()->inRandomOrder()->first();
    if ($number == 1) {
        $companyowner = true;
    }
    elseif ($faker->boolean(60)) { // in company
        $company = null;
    }
    $number++;
    return [
        'user_id' => array_pop($ids),
        'status' => $faker->randomElement(array_keys(App\Models\Lawyer::getStatuses())),
        'cost' => $faker->realText($faker->numberBetween(20, 150)),
        'aboutmyself' => $faker->realText($faker->numberBetween(50, 150)),
        'experience' => $faker->randomElement(array_keys(App\Models\Lawyer::getExperiences())),
        'companyowner' => $companyowner,
        'company_id' => $company ? $company->id : null,
        'expert' => $faker->boolean(80),
    ];
});


