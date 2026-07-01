<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'survey';
    protected $guarded = ['id'];
    protected $casts = ['pertanyaan' => 'array', 'is_aktif' => 'boolean', 'mulai' => 'datetime', 'selesai' => 'datetime'];

    public function jawaban() { return $this->hasMany(SurveyJawaban::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}
