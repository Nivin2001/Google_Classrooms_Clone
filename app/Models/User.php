<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',

    ];
    // public function setEmailAttribute($value)
    // {
    //     $this->attributes['email'] = strtolower($value);//استنخدمنا مصفوفة الاتربيوتس عشان ما ندخل في هىبهىهفغ مخخح
    // }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value),
            set: fn ($value) => strtolower($value),
        );
    }
    public function classrooms()
    {
        return $this->belongsToMany(
            Classroom::class,
            'classroom_user',
            'user_id',
            'classroom_id',
            'id',
            'id',
        )->withPivot('role', 'created_at');
    }

    public function createdClassrooms()
    {
        return $this->hasMany(Classroom::class, 'user_id');
    }

    public function streams()
    {
        return $this->hasMany(Stream::class)
            ->latest();
    }

    public function classworks()
    {
        return $this->belongsToMany(Classwork::class)
            ->withPivot('status', 'grade', 'submitted_at', 'created_at')
            ->using(ClassworkUser::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function profile()
    {
        return $this->HasOne(Profile::class, 'user_id', 'id')
            ->withDefault();
    }

    public function routeNotificationForMail($notification = null)
    { // بنحط اسم الحقل تبع الايميل اذا كان غير email notificationعشان يعرف وين يبعت ال 
        return $this->email;
    }
    public function routeNotificationForVonage($notification = null)
    {
        return '+972594302492' ;
        // return $this->mobile_phone;
    }
    public function recievesBroadcastNotificationsOn()
    {
        return 'Notifications.' . $this->id;
    }

    public function preferredLocale()
    { //لازم نعمللها implement HasLocalePreferences
        return $this->profile->locale;
        // return 'ar';
    }

    public function routeNotificationForHadara()
    {
        return '+972594302492' ;
    }
}
