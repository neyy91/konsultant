<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Lawyer::class => \App\Policies\LawyerPolicy::class,
        \App\Models\Question::class => \App\Policies\QuestionPolicy::class,
        \App\Models\CategoryLaw::class => \App\Policies\CategoryLawPolicy::class,
        \App\Models\Bookmark::class => \App\Policies\BookmarkPolicy::class,
        \App\Models\Theme::class => \App\Policies\ThemePolicy::class,
        \App\Models\Document::class => \App\Policies\DocumentPolicy::class,
        \App\Models\DocumentType::class => \App\Policies\DocumentTypePolicy::class,
        \App\Models\Call::class => \App\Policies\CallPolicy::class,
        \App\Models\Answer::class => \App\Policies\AnswerPolicy::class,
        \App\Models\Clarify::class => \App\Policies\ClarifyPolicy::class,
        \App\Models\Complaint::class => \App\Policies\ComplaintPolicy::class,
        \App\Models\Like::class => \App\Policies\LikePolicy::class,
        \App\Models\Chat::class => \App\Policies\ChatPolicy::class,
        \App\Models\Expertise::class => \App\Policies\ExpertisePolicy::class,
        \App\Models\File::class => \App\Policies\FilePolicy::class,
        \App\Models\Company::class => \App\Policies\CompanyPolicy::class,
        \App\Models\City::class => \App\Policies\CityPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
