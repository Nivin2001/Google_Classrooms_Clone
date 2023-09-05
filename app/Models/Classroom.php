<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use App\Observers\ClassroomObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Classwork;
use Exception;

class Classroom extends Model
{
    use HasFactory;
    use SoftDeletes;
    public static string $disk = 'uploads';

    protected $fillable = [
        'name', 'subject', 'section', 'room', 'user_id',
        '_Token', 'theme', 'cover_image_path', 'code',
    ];

    public function scopeFilter(Builder $query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('name', 'like', '%' . request('search') . '%')
                ->orWhere('section', 'like', '%' . request('search') . '%')
                ->orWhere('subject', 'like', '%' . request('search') . '%')
                ->orWhere('room', 'like', '%' . request('search') . '%');
        }
    }


    public static function uploadCoverImage($file)
    {
        $path = $file->store('/covers', [
            'disk' => self::$disk,
        ]);
        return $path;
    }

    public static function deleteCoverImage($path)
    {
        if ($path && Storage::disk(Classroom::$disk)->exists($path))
            return Storage::disk(self::$disk)->delete($path);
    }
    // protected static function booted()
    protected static function boot()
    {
        parent::boot(); //عنا فنكشن boot فكانه بنعمل الها over ride ف رح تعمللنا مشاكل 
        // static::addGlobalScope('user',function(Builder $query){
        //     $query->where('user_id' ,'=', Auth::id());
        // });
        static::addGlobalScope(new UserClassroomScope);
        // static::creating(function (Classroom $classroom) {
        //     $classroom->code = Str::random(8);
        //     $classroom->user_id =Auth::id();
        // });
        // static::forceDeleted(function (Classroom $classroom){
        //     static::deleteCoverImage($classroom->cover_image_path);
        // });
        // static::deleted(function (Classroom $classroom){
        //     $classroom->status = 'deleted';
        //     $classroom->save();
        // });
        // static::restored(function (Classroom $classroom){
        //     $classroom->status = 'active';
        //     $classroom->save();
        // });
    }

    public function classworks(): HasMany
    {
        return $this->hasMany(Classwork::class, 'classroom_id', 'id');
    }
    
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class,'classroom_id','id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'classroom_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'classroom_user',
            'classroom_id',
            'user_id',
            'id',
            'id',
        )->withPivot('role', 'created_at');
        // ->as('join');//لو بدنا نغير كلمة pivot لو في عمود اسمه pivot مثلا
    }

    public function teachers()
    {
        return $this->users()->wherePivot('role', 'teacher');
    }

    public function students()
    {
        return $this->users()->wherePivot('role', 'student');
    }
    public function streams()
    {
        return $this->hasMany(Stream::class)
        ->latest();
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function scopeActive(Builder $query)
    {
        $query->where('status', '=', 'active');
    }

    public function scopeRecent(Builder $query)
    {
        $query->orderBy('updated_at', 'DESC');
    }

    public function scopeStatus(Builder $query, $status = 'active')
    {
        $query->where('status', '=', 'active');
    }

    public function join($user_id, $role = 'student')
    {
        $exists = $this->users()->wherePivot('user_id', $user_id)->exists();
        if ($exists) {
            throw new Exception('User already joined the classroom');
        }
        return $this->users()->attach($user_id, [
            'role' => $role,
            'created_at' => now(),
        ]); //رح تعمل insert بالجدول الوسيط للبخقهلى نثغس 
        //رح ترجعلنا 1|0  h`h k[pj hgulgdm h, gh]
        // return DB::table('classroom_user')->insert([
        //     'classroom_id' => $this->id,
        //     'user_id' => $user_id,
        //     'role' => $role,
        //     'created_at' => now(),
        // ]);
    }

    // protected $guarded = [ 'id'];//not recommended
    // public function getRouteKeyName()
    // {
    //     //حيفهم هان انو اباراميتر تبع الراوتس هو الكود
    //     return 'code';
    // }
    //accessores must have return ;
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function getCoverImageUrlAttribute() //ما رح نمرر فاليو لانه اساس الاتربيوت مش موجود
    {
        //$classroom->cover_image_url
        if ($this->cover_image_path) {
            return Storage::disk('uploads')->url($this->cover_image_path);
        }
        return 'https://placehold.co/800x300';
    }

    public function getUrlAttribute()
    {
        return route('classrooms.show', $this->id);
    }

    public function getInvitationLinkAttribute()
    {
        $invitation_link = URL::signedRoute('classrooms.join', [
            'classroom' => $this->id,
            'code' => $this->code,
        ]);
        return $invitation_link;
    }
}
