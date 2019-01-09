<?php

namespace App\Events\Complaint;

use Illuminate\Queue\SerializesModels;


/**
 * Создание жалобы.
 */
class ComplaintCreate extends ComplaintEvent
{
    use SerializesModels;

}
