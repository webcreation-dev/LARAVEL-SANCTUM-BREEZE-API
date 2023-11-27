<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'email',
        'phone_number',
        'frame',
        'reference',
        'color',
        'price',
        'left_eye_vl_correction',
        'left_eye_vp_correction',
        'right_eye_vl_correction',
        'right_eye_vp_correction',
    ];

    public function sells() {
        return $this->hasMany(Sell::class, 'patient_id');
    }

    public function user() {
        return $this->belongsTo(Property::class, 'user_id');
    }
}
