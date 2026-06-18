<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyReport extends Model
{
    use HasFactory;

    protected $table = 'emergency_reports';

    protected $fillable = [
        'nama',
        'no_hp',
        'jenis_kejadiaan',
        'deskripsi',
        'latitude',
        'longitude',
        'foto_path',
        'captcha_answer',
        'captcha_token',
    ];
}

