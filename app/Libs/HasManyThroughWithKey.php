<?php 

namespace App\Libs;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Model;


/**
* HasManyThrough with set key on join left field.
*/
class HasManyThroughWithKey extends HasManyThrough
{

    /**
     * Parent key on join left field.
     * @var string
     */
    protected $leftKey;

    /**
     * @parent
     */
    public function __construct(Builder $query, Model $farParent, Model $parent, $leftKey, $firstKey, $secondKey, $localKey)
    {
        $this->leftKey = $leftKey;

        parent::__construct($query, $farParent, $parent, $firstKey, $secondKey, $localKey);
    }

    /**
     * @parent
     */
    public function getQualifiedParentKeyName()
    {
        return $this->parent->getTable() . '.' . $this->leftKey;
    }
}