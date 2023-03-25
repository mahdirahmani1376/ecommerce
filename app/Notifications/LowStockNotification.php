<?php

namespace App\Notifications;

use App\Enums\StockEnum;
use App\Models\ProductVendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected ProductVendor $productVendor;

    public function __construct(
        ProductVendor $productVendor
    )
    {
        $this->productVendor = $productVendor;
    }

    public function via($notifiable): array
    {
        return ['mail','database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $product = $this->productVendor->product;
        return (new MailMessage)
            ->line('Your product with name:'.$product->name.' has less than'.str(StockEnum::LowStockEnum).'stock available')
            ->action('Product url:', route('products.view',$product))
            ->line('Please consider that')
            ;
    }

    public function toArray($notifiable): array
    {
        return [
            'productVendor' => $this->productVendor
        ];
    }
}
