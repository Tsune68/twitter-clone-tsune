<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = ['follow_user_id', 'follower_user_id'];

}
