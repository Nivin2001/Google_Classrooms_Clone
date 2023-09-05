<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class PostObserver
{
    //creating,created,updating,updated ,saving ,saved
    //Deleting ,Deleted , Restoring,Restored,ForceDeleteing
    //ForceDeleted,Retrieved
    /**
     * Handle the Post "created" event.
     */
    public function creating(Post $post): void
    {
        $post->user_id =Auth::id();
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
       //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
    //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
       //
    }
}
