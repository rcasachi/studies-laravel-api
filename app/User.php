<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function type() {
        return 'users';
    }

    public function allowedAttributes(){
        return collect($this->attributes)->filter(function($item, $key) {
            return !collect($this->hidden)->contains($key) && $key !== 'id';
        })->merge([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
