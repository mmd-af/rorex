<?php

namespace App\Models\InvoiceOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceOrder extends Model
{
    use HasFactory,
        InvoiceOrderRelationships,
        InvoiceOrderModifiers;

    protected $table = 'invoice_orders';
}
