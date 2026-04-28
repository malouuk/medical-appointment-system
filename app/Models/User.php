<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Les champs qu'on peut modifier en masse
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Les champs qu'on ne veut pas afficher en JSON (password, etc)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Convertir les types de données
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Un utilisateur a plusieurs rendez-vous
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
