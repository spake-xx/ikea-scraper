<?php

namespace App\Services\TelegramCommands;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class RemoveCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Remove';

    protected $usage = '/remove';

    /**
     * @var string
     */
    protected $description = 'Usuń przedmiot.';

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
        $message = $this->getMessage();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();

        $subscription = Subscription::find(1);
        if (!$subscription) {
            return $this->replyToChat("Nie ma żadnych śledzonych obecnie przedmiotów.");
        }
        return $this->replyToChat("Lista przedmiotów w bazie: " . PHP_EOL . implode("," . PHP_EOL, $subscription->queries));
    }
}