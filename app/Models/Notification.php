<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'status'];

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
