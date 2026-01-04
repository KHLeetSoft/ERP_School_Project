<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\ProductPlan;

class ProductPlanNotification extends Notification
{
    use Queueable;

    public $productPlan;

    public function __construct(ProductPlan $productPlan)
    {
        $this->productPlan = $productPlan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
       return [
            'title' => 'New Product Plan Added',
            'body' => "A new software plan '{$this->plan->title}' is now available at ₹{$this->plan->price}.",
            'plan_id' => $this->plan->id,
            'link' => route('admin.productplans.view', $this->plan->id), // Create this route if needed
        ];
    }
}
