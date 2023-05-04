<?php

namespace App\Services;

use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class NotificationsServices
{
    public function sendNotificationToUser($user, $title, $message, $actions)
    {
        return $user->notify(
            Notification::make()
                ->title($title)
                ->body($message)
                ->actions($actions)
                ->toDatabase(),
        );
    }
}
