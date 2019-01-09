<?php 

namespace App\Libs;

use App\Helper;

/**
 * Функционал генерации и автогенерации slug.
 */
trait Slug {

    /**
     * Bootstrap any application services
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            if (!$model->slug) {
                $model->slug = $model->generateSlug();
                $model->save();
            }
        });
    }

    /**
     * Генерация slug.
     * @return string
     */
    public function generateSlug(array $fields = [])
    {
        if (empty($fields)) {
            $fields = $this->toArray();
        }
        $slugField = $this->getSlugField();
        return Helper::slug($fields[$slugField], [$this->id]);
    }

    protected function getSlugField()
    {
        return defined('static::SLUG_FIELD') ? static::SLUG_FIELD : 'name';
    }

}