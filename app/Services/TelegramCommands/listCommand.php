<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class listCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'list';

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