<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyJawaban extends Model
{
    protected $table = 'survey_jawaban';
    protected $guarded = ['id'];
    protected $casts = ['jawaban' => 'array'];

    public function survey() { return $this->belongsTo(Survey::class); }
    public function anggota() { return $this->belongsTo(Anggota::class); }
}
