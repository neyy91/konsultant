<?php 

namespace App\Repositories;

use App\Models\Document;
use App\Models\City;

/**
* Репозиторий для документов.
*/
class DocumentRepository
{

    /**
     * @var integer
     */
    const DEFAULT_PAGE = 10;

    /**
     * @var integer
     */
    const DEFAULT_TAKE = 10;


    /**
     * Получение всех городов.
     * @param  array  $options
     * @return array
     */
    public function getAll()
    {
        $options = array_merge($this->options, $options);
        return Document::setDefault()->get()->all();
    }

    /**
     * Документы постранично.
     * @param  integer $pages
     * @param  array   $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($page = null)
    {
        $page = $page ? $page : config('site.document.page', self::DEFAULT_PAGE);
        return Document::setDefault()->paginate($page);
    }

    /**
     * Получения id
     * @param  mixed|integer $model
     * @return integer
     */
    protected function getId($model)
    {
        if (isset($model->id)) {
            $model = $model->id;
        }
        else {
            $model = $model;
        }
        return $model;
    }

    /**
     * Документы типа документа постранично.
     * @param  DocumentType|integer $documentType
     * @param  integer              $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByDocumentType($documentType, $page = null)
    {
        $page = $page ? $page : config('site.document.page', self::DEFAULT_PAGE);
        return Document::setDefault()->where('document_type_id', $this->getId($documentType))->paginate($page);
    }

    /**
     * Документы пользователя постранично.
     * @param  User|integer $city
     * @param  integer      $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByCity($city, $page = null)
    {
        $page = $page ? $page : config('site.document.page', self::DEFAULT_PAGE);
        return Document::setDefault()->where('city_id', $this->getId($city))->paginate($page);
    }

    /**
     * Документы типа документа.
     * @param  DocumentType|integer $documentType
     * @param  integer              $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByDocumentType($documentType, $take = null)
    {
        $take = $take ? $take : config('site.document.take', self::DEFAULT_TAKE);
        return Document::setDefault()->where('document_type_id', $this->getId($documentType))->take($take)->get();
    }

    /**
     * Документы пользователя.
     * @param  User|integer $user
     * @param  integer      $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByUser($user, $page = null)
    {
        $page = $page ? $page : config('site.user.order.page', self::DEFAULT_PAGE);
        return Document::setDefault()->where('user_id', $this->getId($user))->paginate($page);
    }


    /**
     * Документы по городам
     * @param  City|integer $city
     * @param  integer      $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByCity($city, $take = null)
    {
        $take = $take ? $take : config('site.document.take', self::DEFAULT_TAKE);
        return Document::setDefault()->where('city_id', $this->getId($city))->take($take)->get();

    }

    /**
     * Документы пользователя.
     * @param  User|integer $user
     * @param  integer      $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByUser($user, $take = null)
    {
        $take = $take ? $take : config('site.user.order.take', self::DEFAULT_TAKE);
        return Document::setDefault()->where('user_id', $this->getId($user))->take($take)->get();
    }

}