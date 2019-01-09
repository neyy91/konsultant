<?php

namespace App\Events\Document;

use Illuminate\Queue\SerializesModels;


/**
 * Создание документа.
 */
class DocumentCreate extends DocumentEvent
{
    
    use SerializesModels;

}