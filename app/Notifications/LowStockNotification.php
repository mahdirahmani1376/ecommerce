<?php

namespace App\Notifications;

use App\Enums\StockEnum;
use App\Models\VariationVendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected VariationVendor $variationVendor
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $product = $this->variationVendor->variation->Product;

        return (new MailMessage)
            ->line('Your product with name:'.$product->name.' has less than'.StockEnum::LowStockEnum->value.'stock available')
            ->action('Product url:', route('products.view', $product))
            ->line('Please consider that');
    }

    public function toArray($notifiable): array
    {
        return [
            'variationVendor' => $this->variationVendor,
        ];
    }
}
