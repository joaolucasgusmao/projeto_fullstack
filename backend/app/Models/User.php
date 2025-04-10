<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "avatar",
        "name",
        "email",
        "password",
        "is_admin",
        "birth_date",
        "phone_number",
        "gender"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "password",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "password" => "hashed",
    ];

    public function setBirthDateAttribute($value)
    {
        $this->attributes["birth_date"] = Carbon::createFromFormat("d/m/Y", $value)->format("Y-m-d");
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function real_state()
    {
        return $this->hasMany(RealState::class);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
        ] + parent::toArray();
    }
}
