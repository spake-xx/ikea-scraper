<?php

namespace App\Services;

use App\Services\TelegramCommands\channelpostCommand;
use App\Services\TelegramCommands\ListCommand;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;

class TelegramBot
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Telegram(
            config('services.telegram.bot_api_key'),
            config('services.telegram.bot_username')
        );
        $this->telegram->setCommandsPath(app_path('Services/TelegramCommands'));
    }

    public function setWebhook($url)
    {
        try {
            $result = $this->telegram->setWebhook($url);
            if ($result->isOk()) {
                return true;
            }
            return false;
        } catch (TelegramException $e) {
            return false;
        }
    }

    public function removeWebhook()
    {
        try {
            $result = $this->telegram->deleteWebhook();
            if ($result->isOk()) {
                return true;
            }
            return false;
        } catch (TelegramException $e) {
            return false;
        }
    }

    public function handle()
    {
        $this->telegram->handle();
        Log::info('handled');
        return true;
    }

    public function sendMessage($chatId, $text, $parseMode = 'HTML', $disableWebPagePreview = true)
    {
        try {
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => $disableWebPagePreview,
            ];

            $result = \Longman\TelegramBot\Request::sendMessage($data);
            return $result->isOk();
        } catch (TelegramException $e) {
            return false;
        }
    }

    public function sendPhoto($chatId, $photo, $caption = '', $parseMode = 'HTML')
    {
        try {
            $data = [
                'chat_id' => $chatId,
                'photo' => $photo,
                'caption' => $caption,
                'parse_mode' => $parseMode,
            ];
            $result = \Longman\TelegramBot\Request::sendPhoto($data);
            return $result->isOk();
        } catch (TelegramException $e) {
            return false;
        }
    }

    public function getTelegram()
    {
        return $this->telegram;
    }
}
