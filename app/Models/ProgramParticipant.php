<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramParticipant extends Model
{
    use SoftDeletes;

    protected $fillable = ['program_id', 'entity_type', 'entity_id'];

    protected $dates = ['deleted_at'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
