<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuscleGroup extends Model
{
    protected $table = 'muscle_group';
    public function portions()
    {
        return $this->hasMany(MusclePortion::class, 'muscle_group_id');
    }
}
