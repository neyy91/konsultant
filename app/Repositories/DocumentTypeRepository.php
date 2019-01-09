<?php 

namespace App\Repositories;

use App\Models\DocumentType;

/**
* Репозиторий типов документа.
*/
class DocumentTypeRepository extends CategoryLawRepository
{

    /**
     * Получение всех типов документа с потомками и с жадной загрузкой(eager loading).
     * @param integer $level
     * @return array
     */
    public function parentsWithChilds($level, $with = [])
    {
        $parents =  DocumentType::setDefault();
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
            $parents->with($childs);
            if (!empty($with)) {
                $parents->with($with);
            }
        }
        return $parents->get()->all();
    }

}