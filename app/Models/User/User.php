<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee\Employee;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'cod_staff',
        'email',
        'password',
        'is_active'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //    public function getIsActiveAttribute($is_active)
    //    {
    //        return $is_active ? 'Active' : 'Deactivate';
    //    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function company()
    {
        return $this->hasOne(Company::class);
    }
}
