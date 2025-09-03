<?php
namespace Ysn\SuperCore\Notifications;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    //public $afterCommit = true;
    public function __construct()
    {
        //$this->afterCommit = true;
    }

    public function via($notifiable)
    {

        //return $notifiable->prefers_sms ? ['nexmo'] : ['mail', 'database'];
        return [
            'database',
            //'broadcast',
            //'mail', $account->user->email ??
            // OneSignalChannel::class
        ];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject(__(static::class))
            ->setBody(__('Click here to see details.'));
    }

    /*public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }*/
}
