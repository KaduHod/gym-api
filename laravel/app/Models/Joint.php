<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Joint extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articulations';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
