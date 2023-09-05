<?php

namespace App\Models;

use App\Enums\ClassworkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classwork extends Model
{
    use HasFactory;
    const TYPE_ASSAIGNMENT = ClassworkType::ASSAIGNMENT->value;
    const TYPE_MATERIAL = ClassworkType::MATERIAL->value;
    const TYPE_QUESTION = ClassworkType::QUESTION->value;

    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';

    protected $fillable = [
        'classroom_id','user_id', 'topic_id','title',
        'description','type','status','published_at','options',
    ];
    protected $casts=[
        'options' => 'json', 
        'published_at' => 'datetime',
        // 'type' => ClassworkType::class,
    ];
    public function scopeFilter($query , array $filters)
    {
        if($filters['search'] ?? false){
            $query->where('title', 'like','%' .request('search').'%')
            ->orWhere('description','like','%' .request('search').'%' )
            ->orWhere('type','like','%' .request('search').'%' );
}
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Classwork $classwork){
             if(!$classwork->published_at)
        {
            $classwork->published_at = now();
        }
        });
       
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function getPublishedDateAttribute()
    {
        if($this->published_at)
        return $this->published_at->format('Y-m-d');  
    }

    public function classroom() :BelongsTo
    {
        return $this->belongsTo(Classroom::class ,'classroom_id','id');
    }
     public function topic() :BelongsTo
    {
        return $this->belongsTo(Topic::class ,'topic_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
        
    }
    public function users()
    {
        return $this->belongsToMany(User::class)
        ->withPivot(['status','grade','submitted_at','created_at'])
        ->using(ClassworkUser::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable')
        ->latest();
    }
}
