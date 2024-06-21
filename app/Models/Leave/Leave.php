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
    // protected $fillable = [
    //     'user_id',
    //     'activity_domain',
    //     'vat_id',
    //     'registration_number',
    //     'country',
    //     'county',
    //     'city',
    //     'zip_code',
    //     'address',
    //     'building',
    //     'person_name',
    //     'job_title',
    //     'phone_number'
    // ];
}
