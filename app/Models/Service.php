<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Les champs qu'on peut créer/modifier
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
    ];

    // Garder le prix avec 2 décimales (float)
    protected $casts = [
        'price' => 'decimal:2',
    ];

    // On ajoute display_name comme attribut virtuel
    protected $appends = ['display_name'];

    // Dictionnaire pour traduire les noms des services
    private static $serviceTranslations = [
        'Consultation Générale' => 'messages.service_general_consultation',
        'Consultation Spécialisée' => 'messages.service_specialist_consultation',
        'Bilan de Santé' => 'messages.service_health_checkup',
        'Suivi Post-Opératoire' => 'messages.service_post_operative',
        'Vaccination' => 'messages.service_vaccination',
    ];

    // Retourner le nom traduit du service
    public function getDisplayNameAttribute()
    {
        $key = self::$serviceTranslations[$this->attributes['name']] ?? null;
        return $key ? __($key) : $this->attributes['name'];
    }

    // Traduire automatiquement le nom du service selon la langue
    public function getNameAttribute($value)
    {
        $key = self::$serviceTranslations[$value] ?? null;
        return $key ? __($key) : $value;
    }

    // Un service peut avoir plusieurs rendez-vous
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

