<?php
namespace App\Listeners;

use Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse;
use Illuminate\Support\Facades\Log;

class CheckOrderYandexKassa
{
    /**
     * @param \Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse
     * @return array|null
     */
    public function handle(BeforeCheckOrderResponse $event)
    {
        Log::info('BeforeCheckOrderResponse');
        return null;
    }
}