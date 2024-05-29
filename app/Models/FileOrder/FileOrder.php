<?php

namespace App\Models\FileOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileOrder extends Model
{
    use HasFactory,
        FileOrderRelationships,
        FileOrderModifiers;

    protected $table = 'file_orders';
}
