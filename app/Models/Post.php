<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

     protected $fillable=[
        'user_id','classroom_id','content',
     ];

     protected static function boot()
    {
        parent::boot();
        static::observe(PostObserver::class);

    }
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
    public function scopeFilter($query , array $filters)
    {
        if($filters['search'] ?? false){
            $query->where('content', 'like','%' .request('search').'%');
        }
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function classroom() :BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
