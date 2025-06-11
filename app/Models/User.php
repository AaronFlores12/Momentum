<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // Si ya es una URL completa, devolverla como está
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        // Si empieza con /storage/, construir la URL completa
        if (str_starts_with($this->avatar, '/storage/')) {
            return asset($this->avatar);
        }

        // Si es solo el path del archivo, construir la URL completa
        return asset('storage/' . $this->avatar);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Usuarios que este usuario está siguiendo
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Usuarios que están siguiendo a este usuario
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Verificar si este usuario está siguiendo a otro usuario
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Verificar si este usuario es seguido por otro usuario
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    /**
     * Seguir a un usuario
     */
    public function follow(User $user): bool
    {
        if ($this->id === $user->id) {
            return false; // No puede seguirse a sí mismo
        }

        if ($this->isFollowing($user)) {
            return false; // Ya lo está siguiendo
        }

        $this->following()->attach($user->id);
        return true;
    }

    /**
     * Dejar de seguir a un usuario
     */
    public function unfollow(User $user): bool
    {
        if (!$this->isFollowing($user)) {
            return false; // No lo está siguiendo
        }

        $this->following()->detach($user->id);
        return true;
    }

    /**
     * Obtener el conteo de seguidores
     */
    public function getFollowersCountAttribute(): int
    {
        return $this->followers()->count();
    }

    /**
     * Obtener el conteo de usuarios seguidos
     */
    public function getFollowingCountAttribute(): int
    {
        return $this->following()->count();
    }
}
