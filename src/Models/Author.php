<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Notifications\Notifiable;

class Author extends Authenticatable
{
    //
    // use HasApiTokens, Notifiable;
    use HasApiTokens;

    protected $table = "blog_authors";

    protected $fillable = [
        "name",
        "email",
        "password",
        "additional_information",
        "is_admin",
        "is_disabled",
        "last_login"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
}
