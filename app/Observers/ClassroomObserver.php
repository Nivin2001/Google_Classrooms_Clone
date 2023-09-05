<?php

namespace App\Observers;

use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ClassroomObserver
{
    //creating,created,updating,updated ,saving ,saved
    //Deleting ,Deleted , Restoring,Restored,ForceDeleteing
    //ForceDeleted,Retrieved
    /**
     * Handle the Classroom "created" event.
     */
    public function creating(Classroom $classroom): void
    {
        $classroom->code = Str::random(8);
            $classroom->user_id =Auth::id();
    }

    /**
     * Handle the Classroom "updated" event.
     */
    public function updated(Classroom $classroom): void
    {
        //
    }

    /**
     * Handle the Classroom "deleted" event.
     */
    public function deleted(Classroom $classroom): void
    {
        $classroom->status = 'deleted';
        $classroom->save();
    }

    /**
     * Handle the Classroom "restored" event.
     */
    public function restored(Classroom $classroom): void
    {
        $classroom->status = 'active';
        $classroom->save();
    }

    /**
     * Handle the Classroom "force deleted" event.
     */
    public function forceDeleted(Classroom $classroom): void
    {
        $classroom->deleteCoverImage($classroom->cover_image_path);

    }
}
