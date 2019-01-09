<?php 

namespace App\Repositories;

use App\Models\CategoryLaw;

/**
* Репозиторий категории права для модели CategoryLaw.
*/
class CategoryLawRepository
{

    /**
     * Получени подкатегории.
     * @param  CategoryLaw|\App\Models\DocumentTypes $parent
     * @param  integer $level
     * @param  string $label
     * @param  string $key
     * @param  string $prefix
     * @return array
     */
    protected function getChildsList($parent, $level, $label, $key, $prefix)
    {
        static $level = 1;
        if ($level > $level) {
            return [];
        }
        $childs = $parent->childs;
        $list = [];
        if (!empty($childs)) {
            $level++;
            foreach ($childs as $child) {
                $list[$child->{$key}] = ($prefix ? str_repeat($prefix, $level - 1) : '') . $child->{$label};
                $list += $this->getChildsList($child, $level, $label, $key, $prefix);
            }
        }
        $level--;
        return $list;
    }


    /**
     * Получение масива категории права в виде массива [$key => $label].
     * @param  string $label
     * @param  string $key
     * @return Collection
     */
    public function getList($label = 'name', $key = 'id', $level = 2, $prefix = '&nbsp;&nbsp;&nbsp;')
    {
        $list = [];
        $parents = $this->parentsWithChilds($level);
        foreach ($parents as $parent) {
            $list[$parent->{$key}] = $parent->{$label};
            $list += $this->getChildsList($parent, $level, $label, $key, $prefix);
        }
        return $list;
    }


    /**
     * Получение всех категории права с потомками и с жадной загрузкой(eager loading).
     * @param integer $level
     * @return array
     */
    public function parentsWithChilds($level, $with = [])
    {
        $parents =  CategoryLaw::setDefault()->whereNull('parent_id');
        if ($level > 1) {
            $childs = [];
            $prev = '';
            for ($l = 0; $l  < $level; $l++) { 
                $key = 'childs' . ($l > 0 ? '.' . $prev : '');
                $childs[$key] = function ($query)  {
                    $query->setDefault();
                };
                $prev = $key;
            }
            // dd($childs);
            $parents->with($childs);
            if (!empty($with)) {
                $parents->with($with);
            }
        }
        return $parents->get()->all();
    }

}