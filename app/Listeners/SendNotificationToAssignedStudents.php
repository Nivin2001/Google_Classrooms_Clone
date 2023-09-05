<?php

namespace App\Listeners;

use App\Events\ClassworkCreated;
use App\Models\User;
use App\Notifications\NewClassworkNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationToAssignedStudents
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClassworkCreated $event): void
    {
        //عشان نبعت بس لواحد 
        // $user = User::find(1);
        // $user->notify(new NewClassworkNotification($event->classwork));
       
        //لكل ال يوزرز
        // foreach($event->classwork->users as $user)
        // {
        //     $user->notify(new NewClassworkNotification($event->classwork));
        // }
        
        //باستخدام ال notification facade     
        Notification::send($event->classwork->users , new NewClassworkNotification($event->classwork));

    }
}
