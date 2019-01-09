<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;

use App\Helper;
use App\Libs\Status;
use App\Libs\Slug;
use App\Libs\StatusDefine;
use App\Libs\DateCreatedUpdated;


/**
 * Страницы.
 */
class Page extends Model implements StatusDefine
{
    use Status,
        DateCreatedUpdated,
        Slug;

    protected $fillable = ['status', 'title', 'slug', 'layout', 'page_layout', 'description' , 'text'];

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'page';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Helper::slug($model->title);
            }
        });
    }

    /**
     * Получение ключа для Route.
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function getLyoutsList()
    {
        $layouts = [];
        foreach (config('site.page.layouts') as $layout) {
            $layouts[$layout] = trans('page.layouts.' . $layout);
        }
        return $layouts;
    }

    public static function getPageLayoutsList()
    {
        $layouts = [];
        foreach (config('site.page.page_layouts') as $layout) {
            $layouts[$layout] = trans('page.page_layouts.' . $layout);
        }
        return $layouts;
    }

    /**
     * Название шаблона
     * @return string
     */
    public function getLayoutLableAttribute()
    {
        return trans('page.layouts.' . $this->layout);
    }

    /**
     * Название шаблона страницы.
     * @return string
     */
    public function getPageLayoutLabelAttribute()
    {
        return trans('page.page_layouts.' . $this->page_layout);
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
