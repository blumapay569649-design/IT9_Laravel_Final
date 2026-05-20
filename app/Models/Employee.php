<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'owner_id',
        'employee_name',
        'account_name',
        'role',
        'password',
        'documents',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'documents' => 'array',
        'password' => 'hashed',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
