<?php

namespace App\Notifications;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to a vehicle owner when their vehicle approaches (or passes) its
 * next recommended service interval, based on elapsed mileage or time.
 * Delivered via Laravel's mail channel; swap/add 'nexmo' or a custom SMS
 * channel here if you wire up an SMS provider.
 */
class ServiceReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public Vehicle $vehicle)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $kmLeft = $this->vehicle->kmUntilNextService();

        return (new MailMessage)
            ->subject("Service reminder for {$this->vehicle->plate_number}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Your {$this->vehicle->make} {$this->vehicle->model} ({$this->vehicle->plate_number}) is due for its next service.")
            ->when($kmLeft !== null, fn ($mail) => $mail->line("Approximately {$kmLeft} km remaining before the recommended service interval."))
            ->action('Book a Service Appointment', route('appointments.create'))
            ->line('Regular maintenance keeps your vehicle safe and reliable.');
    }
}
