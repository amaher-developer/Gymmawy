<?php

namespace Modules\Access\Models;

use Modules\Access\Events\UserCreating;
use Modules\Gym\Models\GymBrand;
use Modules\Gym\Models\GymFavorite;
use Modules\Gym\Models\GymMember;
use Modules\Gym\Models\GymSubscription;
use Modules\Trainer\Models\TrainerFavorite;
use App\Notifications\ResetPassword;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use LaravelEntrustUserTrait;

    public static $uploads_path = 'uploads/users/';
    public static $thumbnails_uploads_path='uploads/users/thumbnails/';


    protected $appends = ['image_thumbnail'];

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $dispatchesEvents = ['creating' => UserCreating::class];

    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }
    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPassword($token));
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getNameAttribute($name)
    {
        return $name;
    }
    public function getImageAttribute($image)
    {
        if($image)
        {
            return asset(self::$uploads_path.$image);
        }
        else
            return asset('resources/assets/front/img/logo/default.png');
    }

    public function getImageThumbnailAttribute()
    {
        if ($this->image) {
            return str_replace(self::$uploads_path,self::$thumbnails_uploads_path , $this->image);
        } else
            return $this->image;
    }
    public function gym_favorites()
    {
        return $this->hasMany(GymFavorite::class, 'user_id');
    }
    public function trainer_favorites()
    {
        return $this->hasMany(TrainerFavorite::class, 'user_id');
    }

    public function gym()
    {
        return $this->hasOne(GymBrand::class, 'user_id');
    }


//    public function subscription()
//    {
//        return $this->hasMany(GymSubscription::class, 'gym_id');
//    }
//
//    public function member()
//    {
//        return $this->hasMany(GymMember::class, 'gym_id');
//    }

    public function scopeAdmins($query)
    {
        $query->has('roles');
    }

    public function scopeUsers($query)
    {
        $query->has('roles', '<', 1);
    }


    public function toggleBlock()
    {
        $this->block = !$this->block;

        return $this;
    }


    public function getPermsAttribute()
    {
        $perms = [];
        if (empty($this->permissions)) {
            if (!request()->is('api/*')) {
                $roles = $this->roles;
                foreach ($roles as $role) {
                    $perms = array_merge($perms, $role->perms()->pluck('permissions.name')->toArray());
                }
            }
            $this->permissions = $perms;
            return $perms;
        } else
            return $this->permissions;
    }

    public function toArray()
    {

        $to_array_attributes = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ];
        foreach ($this->relations as $key => $relation) {
            $to_array_attributes[$key] = $relation;
        }
        foreach ($this->appends as $key => $append) {
            $to_array_attributes[$key] = $append;
        }
        return $to_array_attributes;
    }

    public function updateApiToken()
    {
        do {
            $apiToken = bin2hex(openssl_random_pseudo_bytes(30));
        } while (self::select('id')->where('api_token', $apiToken)->exists());
        $this->api_token = $apiToken;
        return $this;
    }

}

