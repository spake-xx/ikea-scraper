<?php

namespace App\Http\Controllers;

use App\Services\TelegramBot;
use Illuminate\Http\Request;

class HookController extends Controller {
    private $telegramBot;

    public function __construct(TelegramBot $telegramBot) {
        $this->telegramBot = $telegramBot;
    }

    public function hook(Request $request) {
        if($request->get('secret') !== config('services.telegram.webhook_secret')) {
            abort(401);
        }
        
        if($this->telegramBot->handle()) {
            return response()->json(['status' => 'ok']);
        } else {
            abort(500);
        }
    }
}