<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;
    use SoftDeletes;
    // const CREATED_AT ='created_at';
    // const UPDATED_AT = 'updated_at';
    // protected $connection = 'mysql';
    // protected $table = 'topics';
    // protected $primaryKey = 'id';
    // protected $keyType = 'int';
    // public $incrementing =true;
    public $timestamps = false;
    protected $fillable = [
        'name' ,'classroom_id','user_id',
    ];

    public function classworks(): HasMany
    {
        return $this->hasMany(classwork::class ,'topic_id','id');
    }
    public function scopeMyClassroom(Builder $query , $classroomId)
    {
        $query->where('classroom_id', $classroomId);
    }
    
}
