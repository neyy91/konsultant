<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use App\Libs\HasManyThroughWithKey;

/**
* Model
*/
class Model extends EloquentModel
{
    
    /**
     * Define a has-many-through relationship with join left key.
     *
     * @param  string  $related
     * @param  string  $through
     * @param  string  $leftKey
     * @param  string|null  $firstKey
     * @param  string|null  $secondKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function hasManyThroughWithKey($related, $through, $leftKey, $firstKey = null, $secondKey = null, $localKey = null)
    {
        $through = new $through;

        $firstKey = $firstKey ?: $this->getForeignKey();

        $secondKey = $secondKey ?: $through->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return new HasManyThroughWithKey((new $related)->newQuery(), $this, $through, $leftKey, $firstKey, $secondKey, $localKey);
    }

}