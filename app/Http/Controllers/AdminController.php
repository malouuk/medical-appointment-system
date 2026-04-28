<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::withCount('appointments')->orderBy('role')->get();
        $stats = [
            'total_users'    => User::count(),
            'total_patients' => User::where('role','patient')->count(),
            'total_doctors'  => User::where('role','medecin')->count(),
            'total_appts'    => Appointment::count(),
        ];
        return view('admin.users', compact('users', 'stats'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:patient,medecin,admin',
        ]);
        $user->update(['role' => $request->role]);
        return redirect()->route('admin.users')
            ->with('success', "Rôle de {$user->name} mis à jour → {$request->role}");
    }
}
