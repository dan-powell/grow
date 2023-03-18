<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FigureAlertResolved extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($figure, $alert, $datum)
    {
        $this->figure = $figure;
        $this->alert = $alert;
        $this->datum = $datum;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Alert resolved - ' . $this->figure->name . ' (' . $this->figure->device->name . ')')
            ->line('An alert for ' . $this->figure->name . ' (' . $this->figure->device->name . ') has been resolved')
            ->line('Device: ' . $this->figure->device->name)
            ->line('Reading: ' . $this->figure->name)
            ->line('Alert Trigger Value: ' . $this->alert->value)
            ->line('Reading Value: ' . $this->datum->value_string);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }
}
