<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Models\Subscription;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

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
        $message = $this->getMessage();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();

        $subscription = Subscription::find(1);
        return $this->replyToChat("Lista przedmiotów w bazie: " . PHP_EOL . implode("," . PHP_EOL, $subscription->queries));
    }
}