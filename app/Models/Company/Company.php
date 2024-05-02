<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory,
        SoftDeletes,
        CompanyRelationships,
        CompanyModifiers;

    protected $table = 'companies';
    protected $fillable = [
        'user_id',
        'company_name',
        'activity_domain',
        'vat_id',
        'registration_number',
        'country',
        'county',
        'city',
        'zip_code',
        'address',
        'building',
        'person_name',
        'job_title',
        'phone_number'
    ];
}
