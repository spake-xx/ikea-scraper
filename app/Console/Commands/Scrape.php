<?php

namespace App\Console\Commands;

use App\Models\Result;
use App\Providers\IkeaProvider;
use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Services\TelegramBot;

class Scrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(TelegramBot $telegramBot): void
    {
        $subscription = Subscription::find(1);
        $api = new IkeaProvider();
        $this->info('Skrejpowanie...');
        $products = $api->getOkazje(['metod']);
        $lastFetchedProducts = Result::orderBy('id', 'desc')->first();
        $newProducts = $products;
        if(!empty($lastFetchedProducts)) {
            $newProducts = [];
            $newProducts = array_filter($products, function($product) use ($lastFetchedProducts) {
                $lastFetchedProductsIds = array_column($lastFetchedProducts->result, 'id');
                return !in_array($product['id'], $lastFetchedProductsIds);
            });
        }
        Result::insert([
            'subscription_id' => $subscription->id,
            'result' => json_encode($products),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $template = "
<b>🔥 Okazja IKEA 🔥</b>
{productTitle}
{productDescription}
{productAdditionalInfo}
{productPrice} {currency}
{productReasonDiscount} {productReasonForDiscount}
<a href='{productLink}'>🔗 Link do produktu</a>
        ";
        foreach($newProducts as $product){
            $telegramBot->sendPhoto(1751230938, $product['heroImage']);
            $data = [
                '{productTitle}' => $product['title'],
                '{productDescription}' => $product['description'],
                '{productAdditionalInfo}' => $product['additionalInfo'],
                '{productPrice}' => $product['price'],
                '{productReasonDiscount}' => $product['reasonDiscount'],
                '{productReasonForDiscount}' => $product['reasonForDiscount'],
                '{currency}' => $product['currency'],
                '{productLink}' => 'https://www.ikea.com/pl/pl/customer-service/services/okazje-na-okraglo-pub63b48c50/#/katowice/' . $product['id'],
            ];
            $telegramBot->sendMessage(1751230938, strtr($template, $data));
        }
    }
}
