<?php 

namespace App\Libs;

use Auth;
use Gate;

use App\Models\User;

trait ScopePublishedGeneral {

    /**
     * Опубликовано.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query, $ignore = false)
    {
        $statuses = [StatusGeneralDefine::STATUS_BLOCKED];
        if ($ignore || Gate::denies('unpublished', User::class)) {
            $statuses[] = StatusGeneralDefine::STATUS_UNPUBLISHED;
        }
        return $query->whereNotIn('status', $statuses);
    }

}