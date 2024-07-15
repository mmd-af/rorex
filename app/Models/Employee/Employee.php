<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory,
        EmployeeRelationships,
        EmployeeModifiers;

    protected $table = 'employees';

}
