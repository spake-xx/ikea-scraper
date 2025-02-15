<?php

namespace App\Services\TelegramCommands;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class ListCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'List';

    protected $usage = '/list';

    /**
     * @var string
     */
    protected $description = 'Lista przedmiotów, o których dostajesz powiadomienia.';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        Log::info('ListCommand executed');
        $message = $this->getChannelPost();
        $command = $message->getCommand();

        $subscription = Subscription::find(1);
        if (!$subscription) {
            return Request::sendMessage([
                'chat_id' => '-1002252043619',
                'text' => "Nie ma żadnych śledzonych obecnie przedmiotów." . $command,
            ]);
        }
        return Request::sendMessage([
            'chat_id' => '-1002252043619',
            'text' => "Nie ma żadnych śledzonych obecnie przedmiotów." . $command,
        ]);    
    }
}