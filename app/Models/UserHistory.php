<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'role',
        'suffix',
        'birth_date',
        'contact_no',
        'home_address',
        'gender',
        'email',
        'verified_status',
        'action',
        'action_at',
    ];
}
