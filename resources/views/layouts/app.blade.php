<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UniFe Archive - Pierre Nzetem</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-unife {
            background-image: url('/images/unife-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .bg-unife::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.55);
            z-index: -1;
        }
        .main-content {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(2px);
            border-radius: 16px;
            padding: 20px;
        }
        .marquee-container { width: 100%; overflow: hidden; }
        .marquee-content {
            display: inline-block;
            animation: marquee-scroll 25s linear infinite;
            white-space: nowrap;
        }
        @keyframes marquee-scroll {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .marquee-container:hover .marquee-content { animation-play-state: paused; }
        [x-cloak] { display: none !important; }
    </style>
</head>

@php
    $isAdmin = Auth::check() && Auth::user()->isAdmin();

    // Calcul unique du nombre d'utilisateurs en attente (visible uniquement par l'admin)
    $pendingCount = 0;
    if ($isAdmin) {
        $pendingCount = \App\Models\User::where('is_approved', false)
                            ->where('role', '!=', 'admin')
                            ->count();
    }
@endphp

<body class="bg-unife text-slate-100 min-h-screen flex flex-col justify-between font-sans">

    <div>
        {{-- ==================== HEADER ==================== --}}
        <header class="{{ $isAdmin ? 'bg-slate-900/90 border-b-2 border-amber-500 shadow-amber-500/10 backdrop-blur-sm' : 'bg-blue-950/90 border-b-2 border-amber-500 shadow-blue-950/20 backdrop-blur-sm' }} shadow-xl sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center gap-4">

                    {{-- Logo --}}
                    <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 group shrink-0">
                        <div class="w-12 h-12 rounded-xl {{ $isAdmin ? 'bg-gradient-to-tr from-amber-600 to-yellow-400 text-slate-950' : 'bg-gradient-to-tr from-blue-700 to-amber-400 text-white' }} flex items-center justify-center font-black text-xl shadow-lg group-hover:scale-105 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight {{ $isAdmin ? 'text-amber-400' : 'text-white' }} hidden sm:block">
                            UniFe <span class="text-amber-400">Archive</span>
                        </span>
                    </a>

                    {{-- Zone droite --}}
                    <div class="flex items-center gap-3 sm:gap-4 text-sm">

                        {{-- Nom utilisateur --}}
                        <span class="hidden md:inline {{ $isAdmin ? 'text-slate-300' : 'text-blue-200' }}">
                            Benvenuto, <strong class="{{ $isAdmin ? 'text-amber-400' : 'text-white' }}">{{ Auth::user()->name }}</strong>
                        </span>

                        {{-- Bouton Pannello Admin (visible uniquement admin) --}}
                        @if($isAdmin)
                            <a href="{{ route('admin.approvals.index') }}"
                               class="relative inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider px-3 sm:px-4 py-2.5 rounded-xl shadow-md hover:scale-105 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="hidden sm:inline">Pannello Admin</span>
                                <span class="sm:hidden">Admin</span>

                                @if($pendingCount > 0)
                                    <span class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] text-white shadow ring-2 ring-slate-900 font-black">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        {{-- Bouton Déconnexion --}}
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="border {{ $isAdmin ? 'border-amber-500 text-amber-500 hover:bg-amber-500 hover:text-slate-950' : 'border-red-500 text-red-500 hover:bg-red-500 hover:text-white' }} px-3 sm:px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                                <span class="hidden sm:inline">Disconnetti</span>
                                <span class="sm:hidden">Esci</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- ==================== MAIN ==================== --}}
        <main class="max-w-7xl mx-auto px-4 py-8 pb-32">
            <div class="main-content">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- ==================== BANDEAU DÉFILANT ==================== --}}
    <div class="fixed bottom-0 left-0 w-full z-50 overflow-hidden {{ $isAdmin ? 'bg-amber-500/95 border-t border-amber-300' : 'bg-blue-950/95 border-t border-amber-500/50' }} backdrop-blur-md py-3">
        <div class="marquee-container overflow-hidden whitespace-nowrap">
            <div class="marquee-content inline-block">
                <span class="text-sm font-black mx-4 tracking-wider {{ $isAdmin ? 'text-slate-950' : 'text-white' }}">📢 AIUTA I TUOI COLLEGHI!</span>
                <span class="text-sm mx-4 font-bold {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">•</span>
                <span class="text-sm mx-4 font-semibold {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">Hai appena sostenuto un esame? Aggiungi la prova alla banca dati!</span>
                <span class="text-sm mx-4 font-bold {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">•</span>
                <span class="text-sm mx-4 font-semibold {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">Ogni PDF condiviso è un aiuto per chi affronterà lo stesso esame l'anno prossimo.</span>
            </div>
        </div>
    </div>
</body>
</html>