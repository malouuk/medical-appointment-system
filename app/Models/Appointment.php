<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Les champs qu'on peut créer/modifier
    protected $fillable = [
        'user_id',
        'service_id',
        'appointment_date',
        'notes',
        'status',
    ];

    // Convertir appointment_date en objet DateTime
    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    // Un rendez-vous appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un rendez-vous a un service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Filters pour chercher facilement les rendez-vous par statut
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
