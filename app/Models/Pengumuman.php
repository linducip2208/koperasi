<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_published'    => 'boolean',
        'is_highlighted'  => 'boolean',
        'broadcast_wa'    => 'boolean',
        'broadcast_email' => 'boolean',
        'published_at'    => 'datetime',
        'expires_at'      => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now())
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }
}
