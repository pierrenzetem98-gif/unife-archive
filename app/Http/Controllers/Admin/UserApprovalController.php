<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;

class UserApprovalController extends Controller
{
    public function index()
    {
        // L'accès est déjà protégé par le middleware 'admin' (routes/web.php)
        abort_unless(auth()->user()->isAdmin(), 403, 'Accesso riservato all\'amministratore.');

        $pendingUsers = User::where('is_approved', false)->where('role', '!=', 'admin')->latest()->get();
        $approvedUsers = User::where('is_approved', true)->where('role', '!=', 'admin')->latest()->get();

        $communityExams = Exam::with(['subject', 'uploader'])
            ->where('is_community', true)
            ->latest()
            ->get();

        return view('admin.approvals', compact('pendingUsers', 'approvedUsers', 'communityExams'));
    }

    // -- GESTION DES UTILISATEURS --

    public function approve(User $user)
    {
        $user->approve();

        return back()->with('success', "Accesso approvato per {$user->name}!");
    }

    /**
     * Génère un code d'activation à envoyer à l'utilisateur sur Instagram.
     * Le code n'est affiché qu'une seule fois.
     */
    public function generateCode(User $user)
    {
        if ($user->isAdmin() || $user->isApproved()) {
            return back()->withErrors(['error' => "{$user->name} non è in attesa di approvazione."]);
        }

        $code = $user->generateActivationCode();

        return back()->with('activation_code', [
            'name' => $user->name,
            'email' => $user->email,
            'code' => $code,
            'hours' => User::ACTIVATION_TTL_HOURS,
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Non puoi eliminare un admin.']);
        }

        $user->delete();

        return back()->with('success', 'Utente rimosso.');
    }

    // -- GESTION DES EXAMENS --

    public function makeOfficial(Exam $exam)
    {
        $exam->update(['is_community' => false]);

        return back()->with('success', "Il documento '{$exam->title}' è ora nell'Archivio Ufficiale!");
    }
}
