<?php

namespace App\Models\LetterAssignment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LetterAssignment extends Model
{
    use HasFactory,
        LetterAssignmentRelationships,
        LetterAssignmentModifiers;

    protected $table = 'letter_assignments';
}
