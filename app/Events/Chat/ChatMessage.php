<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use App\Models\User;
use App\Models\Chat;


class ChatMessage implements ShouldBroadcastNow
{
    use SerializesModels;

    const CHANNEL_PREFIX = 'chat-';

    /**
     * Сообщение чата.
     * @var Chat
     */
    protected $chat;

    /**
     * ID пользователя.
     * @var integer
     */
    public $from;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->from = $chat->from_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // TODO: Код чата
        return new Channel(self::CHANNEL_PREFIX . $this->chat->to->comet_key);
    }

    /**
     * Условие отправки.
     * @return boolean
     */
    public function broadcastWhen()
    {
        return $this->chat->is == Chat::IS_MESSAGE && $this->chat->message;
    }

    public function broadcastAs()
    {
        return 'chat.messages';
    }
}
