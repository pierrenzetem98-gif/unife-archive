<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    /**
     * Vérifie le code d'activation saisi par un utilisateur en attente.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin() || $user->isApproved()) {
            return redirect()->route('subjects.index');
        }

        $request->validate([
            'code' => ['required', 'string', 'max:20'],
        ], [
            'code.required' => 'Inserisci il codice ricevuto su Instagram.',
        ]);

        switch ($user->activationStatus()) {
            case 'none':
                return back()->withErrors(['code' => "Nessun codice attivo per il tuo account. Scrivi a @pierre.nzetem su Instagram per riceverlo."]);
            case 'expired':
                return back()->withErrors(['code' => 'Il codice è scaduto. Chiedine uno nuovo su Instagram.']);
            case 'locked':
                return back()->withErrors(['code' => 'Troppi tentativi errati. Chiedi un nuovo codice su Instagram.']);
        }

        if (! $user->checkActivationCode($request->input('code'))) {
            $user->increment('activation_attempts');
            $left = max(0, User::MAX_ACTIVATION_ATTEMPTS - $user->activation_attempts);

            return back()->withErrors([
                'code' => $left > 0
                    ? "Codice non valido. Tentativi rimasti: {$left}."
                    : 'Codice non valido. Troppi tentativi: chiedi un nuovo codice su Instagram.',
            ]);
        }

        $user->approve();
        $request->session()->regenerate();

        return redirect()->route('subjects.index')->with('success', 'Account attivato! Benvenuto nell\'Archivio Esami.');
    }
}
