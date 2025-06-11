<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'media',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó el post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los comentarios del post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Accessor para obtener la URL completa de la imagen
     */
    public function getMediaUrlAttribute()
    {
        if (!$this->media) {
            return null;
        }

        // Si ya es una URL completa, devolverla como está
        if (str_starts_with($this->media, 'http')) {
            return $this->media;
        }

        // Si empieza con /storage/, construir la URL completa
        if (str_starts_with($this->media, '/storage/')) {
            return asset($this->media);
        }

        // Si es solo el path del archivo, construir la URL completa
        return asset('storage/' . $this->media);
    }

    /**
     * Scope para obtener posts más recientes
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

     public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}