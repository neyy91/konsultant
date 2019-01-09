<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;

use App\Libs\DateCreatedUpdated;

/**
 * Файлы.
 */
class File extends Model
{

    use DateCreatedUpdated;

    /**
     * Тип
     */
    const ACCESS_PUBLIC = 'public';
    const ACCESS_PRIVATE = 'private';

    /**
     * @var array
     */
    protected $fillable = ['file', 'field'];

    /**
     * @var array
     */
    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Установка атрибутов по располжению файла.
     * @param string $value
     */
    public function setFileAttribute($value)
    {
        if ($this->exists) {
            Storage::delete($this->path);
        }
        if (Storage::exists($value)) {
            $this->attributes['basename'] = pathinfo($value, PATHINFO_BASENAME);
            $this->attributes['dirname'] = pathinfo($value, PATHINFO_DIRNAME);
            $this->attributes['mime_type'] = Storage::getMimetype($value);
            $this->attributes['size'] = Storage::getSize($value);
        }
        else {
            return false;
        }
    }

    /**
     * Путь к файлу.
     * @return string
     */
    public function getPathAttribute()
    {
        return $this->dirname . DIRECTORY_SEPARATOR . $this->basename;
    }

    public function getUrlAttribute()
    {
        return Storage::url(str_replace('public/', '', $this->path));
    }

    /**
     * Полный путь к файлу.
     * @return string
     */
    public function getFullPathAttribute()
    {
        return storage_path('app') . DIRECTORY_SEPARATOR . $this->path;
    }

    /**
     * Статус доступа.
     * @return string|null
     */
    public function getAccessAttribute()
    {
        $access = explode(DIRECTORY_SEPARATOR, $this->dirname, 2);
        return in_array($access, [self::ACCESS_PRIVATE, self::ACCESS_PUBLIC]) ? $access : null;
    }

    /**
     * Получение списка типов файла из таблицы.
     * @return array
     */
    public static function getMimeTypes()
    {
        static $mimeTypes = [];
        if (!empty($mimeTypes)) {
            return $mimeTypes;
        }
        else {
            return $mimeTypes = self::select('mime_type')->distinct()->get()->pluck('mime_type', 'mime_type')->map(function($value, $key)
            {
                $key = str_replace('/', '_', $key);
                return trans("file.mime_type.{$key}");
            })->all();
        }
    }

    /**
     * Получение списка владельцев файла из таблицы в виде {owner_type} => {label owner_type}
     * @return array
     */
    public static function getOwnerTypes()
    {
        static $ownerTypes = [];
        if (!empty($ownerTypes)) {
            return $ownerTypes;
        }
        else {
            return $ownerTypes = self::select('owner_type')->distinct()->get()->pluck('owner_type', 'owner_type')->map(function($value, $key)
            {
                return trans("file.owner_type.{$key}");
            })->all();
        }
    }

    /**
     * Принадлежность файла.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Родитель. Услуга, если ответы.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function parent()
    {
        return $this->morphTo();
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Удаление записи.
     * @param  boolean $withFile С файлом?
     * @return boolean|null
     * @throws Exception
     */
    public function delete($withFile = true)
    {
        if ($withFile) {
            Storage::delete($this->path);
        }
        return parent::delete();
    }
}
