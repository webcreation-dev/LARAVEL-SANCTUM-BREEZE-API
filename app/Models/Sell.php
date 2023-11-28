<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'verre_type',
        'montant',
        'acompte',
        'solde',
        'date_livraison',
        'created_at',
        'updated_at',
    ];
}
