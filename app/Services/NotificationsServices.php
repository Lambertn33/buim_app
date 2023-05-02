<?php

namespace App\Services;

use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;



class NotificationsServices
{
    public function sendNotificationToUser($user, $title, $message)
    {
        $user->notify(
            Notification::make()
                ->title($title)
                ->body($message)
                ->actions([
                    NotificationAction::make('mark as read')
                        ->button()
                ])
                ->toDatabase(),
        );
    }
}
