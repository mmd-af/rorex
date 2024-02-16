<?php

namespace App\Models\StaffRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class StaffRequest extends Model
{
    use HasFactory,
        StaffRequestRelationships,
        StaffRequestModifiers;

    protected $table = 'staff_requests';
}
