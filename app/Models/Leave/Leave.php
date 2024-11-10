<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory,
        LeaveRelationships,
        LeaveModifiers;

    protected $table = 'leaves';

}
