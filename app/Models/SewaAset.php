<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SewaAset extends Model
{
    protected $table = 'sewa_aset';
    protected $guarded = ['id'];
    protected $casts = ['tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];

    public function asset() { return $this->belongsTo(Asset::class); }
    public function anggota() { return $this->belongsTo(Anggota::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}
