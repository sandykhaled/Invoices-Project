<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class AddInvoice extends Notification
{
    use Queueable;
    private $Invoice;

    public function __construct(Invoice $Invoice)
    {
        $this->Invoice=$Invoice;
    }
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->Invoice->id,
            'title'=>'تم إضافة فاتورة جديدة بواسطة :',
            'user'=>auth::user()->name,
        ];
    }
}
