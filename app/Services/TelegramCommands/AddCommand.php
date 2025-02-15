<?php

namespace App\Services\TelegramCommands;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class AddCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Add';

    protected $usage = '/add';

    /**
     * @var string
     */
    protected $description = 'Dodaj nowy przedmiot do śledzenia.';

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

        return $this->replyToChat("Przedmiot został dodany.");
    }
}