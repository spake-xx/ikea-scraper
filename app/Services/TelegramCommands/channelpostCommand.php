<?php

namespace App\Services\TelegramCommands;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class channelpostCommand extends SystemCommand
{
    public const CHAT_ID = '-1002252043619';
    /**
     * @var string
     */
    protected $name = 'List';

    protected $usage = '/List';

    /**
     * @var string
     */
    protected $description = 'Lista przedmiotów, o których dostajesz powiadomienia.';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function __construct() {
        Log::info('created');
    }

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        Log::info('execute');
        $message = $this->getChannelPost();
        $parameters = explode(' ', $message->getText());
        $command = $parameters[0];

        switch($command) {
            case '/list':
                return $this->list();
            case '/add':
                return $this->add($parameters[1]);
            case '/remove':
                return $this->remove($parameters[1]);
            default:
                return $this->notFound();
        }

    }

    public function list() {
        $subscription = Subscription::find(1);
        if (!$subscription) {
            return Request::sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => "Nie ma żadnych śledzonych obecnie przedmiotów.",
            ]);
        }

        return Request::sendMessage([
            'chat_id' => self::CHAT_ID,
            'text' => "Obecnie śledzone przedmioty: " . PHP_EOL . implode(',' . PHP_EOL, $subscription->queries),
        ]);    
    }

    public function add($toAdd) {
        $subscription = Subscription::find(1);
        if(!$subscription) {
            $subscription = new Subscription();
            $subscription->send_to = [self::CHAT_ID];
            $subscription->queries = [$toAdd];
        } else {
            $queries = $subscription->queries;
            $queries[] = $toAdd;
            $subscription->queries = $queries;
        }
        $subscription->save();

        return Request::sendMessage([
            'chat_id' => self::CHAT_ID,
            'text' => "Dodano do listy śledzenia przedmiot: " . $toAdd,
        ]);   
    }

    public function remove($toRemove) {
        $subscription = Subscription::find(1);
        if(!$subscription) {
            return Request::sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => "Nie ma żadnych śledzonych obecnie przedmiotów.",
            ]);
        }
        $queries = $subscription->queries;
        $queries = array_filter($queries, function($query) use ($toRemove) {
            return $query != $toRemove;
        });
        $subscription->queries = $queries;
        $subscription->save();

        return Request::sendMessage([
            'chat_id' => self::CHAT_ID,
            'text' => "Usunięto z listy śledzonych przedmiot: " . $toRemove,
        ]);   
    }

    public function notFound() {
        return Request::sendMessage([
            'chat_id' => self::CHAT_ID,
            'text' => "Hej, nie mam takiego polecenia!",
        ]);
    }
}