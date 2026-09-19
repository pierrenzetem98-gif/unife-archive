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

        <h1 class="text-2xl font-black text-white tracking-wide mb-2">Accesso in Attesa</h1>
        
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 mb-6 text-sm text-amber-200">
            Il tuo account è in attesa di approvazione manuale da parte dell'amministratore (<strong>Pierre Nzetem</strong>).
        </div>

        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Per tutelare la privacy e la sicurezza del materiale didattico del Corso di Informatica di UniFe, tutti gli accessi vengono verificati singolarmente.
        </p>

        <!-- Instagram Badge -->
        <a href="https://instagram.com/pierre.nzetem" target="_blank" rel="noopener noreferrer" 
           class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 text-white text-xs font-bold px-4 py-2 rounded-full shadow hover:scale-105 transition mb-6">
            <span>Contatta @pierre.nzetem su Instagram</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 transition">
                Disconnetti
            </button>
        </form>
    </div>
</body>
</html>