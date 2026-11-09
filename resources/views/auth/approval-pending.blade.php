@php
    $user = auth()->user();
    $status = $user->activationStatus();
@endphp
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account in Attesa di Approvazione - UniFe Archive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-900 border-2 border-amber-500/40 rounded-3xl p-8 shadow-2xl text-center relative overflow-hidden">

        <div class="w-16 h-16 rounded-2xl bg-amber-500/20 border border-amber-400/40 text-amber-400 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-black text-white tracking-wide mb-2">Attiva il tuo account</h1>

        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Per tutelare il materiale didattico del Corso di Informatica di UniFe, ogni accesso viene verificato dall'amministratore (<strong class="text-slate-200">Pierre Nzetem</strong>).
        </p>

        <!-- Étapes -->
        <ol class="text-left text-sm space-y-3 mb-6">
            <li class="flex gap-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-amber-500 text-slate-950 font-black text-xs flex items-center justify-center">1</span>
                <span class="text-slate-300">
                    Scrivi a <strong class="text-amber-400">@pierre.nzetem</strong> su Instagram indicando il tuo nome e l'email
                    <strong class="text-white break-all">{{ $user->email }}</strong>.
                </span>
            </li>
            <li class="flex gap-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-amber-500 text-slate-950 font-black text-xs flex items-center justify-center">2</span>
                <span class="text-slate-300">Riceverai un <strong class="text-white">codice di attivazione</strong> (valido {{ \App\Models\User::ACTIVATION_TTL_HOURS }} ore).</span>
            </li>
            <li class="flex gap-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-amber-500 text-slate-950 font-black text-xs flex items-center justify-center">3</span>
                <span class="text-slate-300">Inseriscilo qui sotto: il tuo account sarà attivato subito.</span>
            </li>
        </ol>

        <a href="https://instagram.com/pierre.nzetem" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 text-white text-xs font-bold px-4 py-2 rounded-full shadow hover:scale-105 transition mb-6">
            <span>Contatta @pierre.nzetem su Instagram</span>
        </a>

        <!-- Statut du code -->
        @if($status === 'active')
            <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-3 mb-4 text-xs text-emerald-300">
                Un codice è stato generato per te. Valido fino al {{ $user->activation_code_expires_at->timezone('Europe/Rome')->format('d/m/Y H:i') }}.
            </div>
        @elseif($status === 'expired')
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-3 mb-4 text-xs text-red-300">
                Il tuo codice è scaduto. Chiedine uno nuovo su Instagram.
            </div>
        @elseif($status === 'locked')
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-3 mb-4 text-xs text-red-300">
                Codice bloccato dopo troppi tentativi. Chiedine uno nuovo su Instagram.
            </div>
        @endif

        <!-- Formulaire du code -->
        <form method="POST" action="{{ route('activation.store') }}" class="mb-6 text-left">
            @csrf
            <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Codice di attivazione</label>
            <input id="code" name="code" type="text" required maxlength="20"
                   autocomplete="one-time-code" autocapitalize="characters" spellcheck="false"
                   placeholder="ABC-123" value="{{ old('code') }}"
                   class="w-full text-center text-2xl font-mono font-black tracking-[0.3em] uppercase bg-slate-950 border-2 {{ $errors->has('code') ? 'border-red-500' : 'border-slate-700' }} focus:border-amber-500 focus:ring-0 rounded-xl py-3 text-white placeholder-slate-700">

            @error('code')
                <p class="mt-2 text-xs text-red-400 font-semibold">{{ $message }}</p>
            @enderror

            <button type="submit" class="mt-4 w-full py-3 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-sm rounded-xl transition">
                Attiva account
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 transition">
                Disconnetti
            </button>
        </form>
    </div>
</body>
</html>
