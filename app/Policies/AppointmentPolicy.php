<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    // Les admins/médecins voient tous les rdv, les patients ne voient que les leurs
    public function view(User $user, Appointment $appointment): bool
    {
        if (in_array($user->role, ['admin', 'medecin'])) {
            return true;
        }
        return $user->id === $appointment->user_id;
    }

    // Les admins/médecins peuvent modifier n'importe quel rdv, les patients seulement les leurs et pas les annulés/complétés
    public function update(User $user, Appointment $appointment): bool
    {
        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return false;
        }
        if (in_array($user->role, ['admin', 'medecin'])) {
            return true;
        }
        return $user->id === $appointment->user_id;
    }

    // Les admins/médecins peuvent annuler n'importe quel rdv, les patients seulement les leurs et pas les annulés/complétés
    public function delete(User $user, Appointment $appointment): bool
    {
        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return false;
        }
        if (in_array($user->role, ['admin', 'medecin'])) {
            return true;
        }
        return $user->id === $appointment->user_id;
    }
}
