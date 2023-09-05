<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'commentable_type', 'commentable_id',
        'content', 'ip', 'user_agent',
    ];

    protected $with = [
        'user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted User',
        ]);
    //لو اليوزر نل بترجعلي واحد فاضي وخلص 
    }

    public function commentable()//رح ترجع مودل من classswork او post 
    {
        return $this->morphTo();// رح يروح يبحث عن اسم الفنكشن لو احنا ما مررنا باراميترز 
    }
}
