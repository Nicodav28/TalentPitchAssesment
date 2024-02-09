<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Challenge extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'difficulty', 'user_id'];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
