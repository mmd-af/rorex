<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Support extends Model
{
    use HasFactory,
        SupportRelationships,
        SupportModifiers;

    protected $table = 'supports';
}
