<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Stream extends Model
{
    use HasFactory, HasUuids;

    //رح يتعرفوا ضمنيا مع ال trait HasUuids
    // public $incrementing = false;
    // protected $keyType = 'string';
    protected $fillable =[
        'id' , 'user_id', 'content',
         'classroom_id','link','created_at'
    ];

    protected static function booted()
    {
        // static::creating(function (Stream $stream){
        //     $stream->id = Str::uuid();
        // });//ممكن نستخدم trait HasUuids
    } 

    // public function uniqueIds()
    // {
    //     //بنعرف الاعمدة يلي من نوع uuid
    //     return[
    //         'id'
    //     ];
    // }
    
 public function getUpdatedAtColumn()
 {
 }
 public function user()
 {
    return $this->belongsTo(User::class);
 }
 public function classroom()
 {
    return $this->belongsTo(Classroom::class);
 }
}
