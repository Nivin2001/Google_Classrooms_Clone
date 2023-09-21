<?php

namespace App\Models;

use App\Concerns\HasPrice;
use DragonCode\Support\Http\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Subscription extends Model
{
    use HasFactory,HasPrice,Prunable; 
    protected $fillable=[
      'plan_id','user_id','price','expires_at','status',
    ];
 protected $casts=[
  'expires_at'=>'datetime',
 ];   
 public function prunable():Builder{
  return static::whereData('expires_at','<=',now()->subYear());
 }
   
     public function user(){
       return $this->belongsTo(User::class);
     }
     public function plan(){
        return $this->belongsTo(Plan::class);

     }
}