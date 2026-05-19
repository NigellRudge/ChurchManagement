<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberMembership extends Model
{
    protected $table = 'member_memberships';
    protected $guarded = [

    ];
    use HasFactory;
}
