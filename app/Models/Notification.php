<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'status', 'type'];

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
