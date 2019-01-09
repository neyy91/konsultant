<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Answer;
use App\Models\Clarify;
use App\Models\User;
use App\Models\Education;
use App\Models\Lawyer;
use App\Models\Company;
use App\Models\Chat;

class RelationProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            Question::MORPH_NAME => Question::class,
            Document::MORPH_NAME => Document::class,
            Call::MORPH_NAME => Call::class,
            Answer::MORPH_NAME => Answer::class,
            Clarify::MORPH_NAME => Clarify::class,
            User::MORPH_NAME => User::class,
            Lawyer::MORPH_NAME => Lawyer::class,
            Education::MORPH_NAME => Education::class,
            Company::MORPH_NAME => Company::class,
            Chat::MORPH_NAME => Chat::class,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
