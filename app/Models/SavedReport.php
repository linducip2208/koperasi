<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedReport extends Model
{
    protected $fillable = ['user_id', 'report_key', 'name', 'params', 'is_favorite', 'last_run_at'];
    protected $casts = ['params' => 'array', 'is_favorite' => 'boolean', 'last_run_at' => 'datetime'];
}
