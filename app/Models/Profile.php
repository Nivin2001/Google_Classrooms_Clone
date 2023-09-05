<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable =[
        'first_name','last_name','gender','locale','timezone',
        'address','birthday',
        

    
    ];
    protected $casts=[
        'birthday' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
