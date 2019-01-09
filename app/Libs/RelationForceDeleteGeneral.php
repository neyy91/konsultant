<?php 

namespace App\Libs;

/**
 * Удаление связей у основных типов.
 */
trait RelationForceDeleteGeneral {

    public static function bootRelationForceDeleteGeneral()
    {
        static::deleting(function($model) {
            foreach (['answers', 'clarifies', 'likes'] as $method) {
                if (method_exists($model, $method)) {
                    $model->$method(false)->get()->each(function($relation) {
                        $relation->delete();
                    });
                }
            }
            // удаление файлов
            if (method_exists($model, 'file')) {
                $model->file()->delete(true);
            }
        });
    }
}