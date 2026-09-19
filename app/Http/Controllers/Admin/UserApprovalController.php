<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accesso riservato all\'amministratore.');
        }

        // 1. Chargement des utilisateurs
        $pendingUsers = User::where('is_approved', false)->where('role', '!=', 'admin')->latest()->get();
        $approvedUsers = User::where('is_approved', true)->where('role', '!=', 'admin')->latest()->get();

        // 2. Chargement des nouveaux examens de la communauté
        $communityExams = Exam::with(['subject', 'uploader'])
            ->where('is_community', true)
            ->latest()
            ->get();

        return view('admin.approvals', compact('pendingUsers', 'approvedUsers', 'communityExams'));
    }

    // -- GESTION DES UTILISATEURS --
    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);
        return back()->with('success', "Accesso approvato per {$user->name}!");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) return back()->withErrors(['error' => 'Non puoi eliminare un admin.']);
        $user->delete();
        return back()->with('success', "Utente rimosso.");
    }

    // -- GESTION DES EXAMENS (Bonus expert) --
    public function makeOfficial(Exam $exam)
    {
        // Basculer un examen communautaire vers l'Archive Historique Officielle
        $exam->update(['is_community' => false]);
        return back()->with('success', "Il documento '{$exam->title}' è ora nell'Archivio Ufficiale!");
    }
}