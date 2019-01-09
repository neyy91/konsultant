<?php 

namespace App\Events\Document;

use App\Models\User;
use App\Models\Document;


abstract class DocumentEvent
{

    /**
     * Вопрос.
     * @var Document
     */
    public $document;

    /**
     * Пользователь
     * @var User
     */
    public $user;

    /**
     * Параметры.
     * @var array
     */
    public $params;

    /**
     * Создание события.
     * @param Document $document
     * @param User     $user
     * @param array   $params
     */
    public function __construct(Document $document, User $user, array $params = [])
    {
        $this->document = $document;
        $this->user = $user;
        $this->params = $params;
    }

}