<x-app-layout>
    <div class="py-10 px-4 max-w-7xl mx-auto" x-data="{ tab: 'utenti' }">

        <!-- En-tête Admin -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-950 border-2 border-amber-500/50 shadow-2xl p-8 text-white mb-8">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black tracking-widest uppercase bg-amber-500 text-slate-950 mb-3">
                    SuperAdmin &bull; Pierre Nzetem
                </div>
                <h1 class="text-3xl font-black tracking-tight text-white">
                    Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-300">Amministratore</span>
                </h1>
                <p class="text-sm text-slate-400 mt-2">
                    Verifica le richieste di accesso via <a href="https://instagram.com/pierre.nzetem" target="_blank" class="text-amber-400 font-bold hover:underline">@pierre.nzetem</a> e modera i file caricati dalla community.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 rounded-2xl text-sm font-bold shadow-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- SÉLECTEUR D'ONGLETS -->
        <div class="flex border-b border-slate-300 dark:border-slate-800 mb-8 gap-4">
            <button @click="tab = 'utenti'" 
                    :class="tab === 'utenti' ? 'border-b-2 border-amber-500 text-amber-500 font-black' : 'text-slate-500 hover:text-slate-300'"
                    class="py-3 px-4 text-sm transition focus:outline-none flex items-center gap-2 uppercase tracking-wider">
                <span>1. Gestione Utenti</span>
                <span class="text-xs bg-red-600 text-white font-bold px-2 py-0.5 rounded-full shadow" x-show="{{ $pendingUsers->count() }} > 0">
                    {{ $pendingUsers->count() }}
                </span>
            </button>

            <button @click="tab = 'esami'" 
                    :class="tab === 'esami' ? 'border-b-2 border-amber-500 text-amber-500 font-black' : 'text-slate-500 hover:text-slate-300'"
                    class="py-3 px-4 text-sm transition focus:outline-none flex items-center gap-2 uppercase tracking-wider">
                <span>2. Nuovi Esami Community</span>
                <span class="text-xs bg-amber-500 text-slate-950 font-bold px-2 py-0.5 rounded-full shadow" x-show="{{ $communityExams->count() }} > 0">
                    {{ $communityExams->count() }}
                </span>
            </button>
        </div>

        <!-- ================= ONGLET 1: GESTION UTILISATEURS ================= -->
        <div x-show="tab === 'utenti'" x-transition class="space-y-8">
            
            <!-- Utilisateurs en attente -->
            <div class="bg-slate-900 rounded-3xl border border-amber-500/30 shadow-xl overflow-hidden">
                <div class="p-5 border-b border-slate-800 bg-slate-950/50">
                    <h2 class="text-lg font-black text-white flex items-center gap-2 uppercase tracking-wider">
                        <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                        In attesa di approvazione ({{ $pendingUsers->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left text-sm">
                        <thead class="text-[11px] uppercase text-slate-500">
                            <tr>
                                <th class="pb-3 px-4">Nome</th>
                                <th class="pb-3 px-4">Email</th>
                                <th class="pb-3 px-4 text-right">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse($pendingUsers as $user)
                                <tr>
                                    <td class="py-4 px-4 font-bold text-white">{{ $user->name }}</td>
                                    <td class="py-4 px-4 text-slate-400">{{ $user->email }}</td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="inline-flex gap-2">
                                            <form method="POST" action="{{ route('admin.approvals.approve', $user->id) }}">
                                                @csrf
                                                <button class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2 rounded-xl transition">
                                                    Approva
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.approvals.destroy', $user->id) }}">
                                                @csrf @method('DELETE')
                                                <button class="bg-slate-800 hover:bg-red-600 text-slate-300 hover:text-white font-bold text-xs px-4 py-2 rounded-xl transition">
                                                    Rifiuta
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-slate-500">Nessun utente in attesa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Utilisateurs Approuvés -->
            <div class="bg-slate-900/50 rounded-3xl border border-slate-800 p-6">
                <h3 class="text-sm font-bold text-slate-400 mb-4 uppercase">Utenti Attivi ({{ $approvedUsers->count() }})</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-400">
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($approvedUsers as $user)
                                <tr>
                                    <td class="py-3 px-2 text-slate-200 font-bold">{{ $user->name }}</td>
                                    <td class="py-3 px-2">{{ $user->email }}</td>
                                    <td class="py-3 px-2 text-right">
                                        <form method="POST" action="{{ route('admin.approvals.destroy', $user->id) }}">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-400 font-bold">Revoca Accesso</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= ONGLET 2: GESTION EXAMENS ================= -->
        <div x-show="tab === 'esami'" x-cloak x-transition class="space-y-6">
            
            <div class="bg-slate-900 rounded-3xl border border-slate-800 shadow-xl overflow-hidden p-6">
                <h2 class="text-lg font-black text-white mb-6 uppercase tracking-wider">
                    File inviati dalla Community ({{ $communityExams->count() }})
                </h2>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($communityExams as $exam)
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-slate-950 border border-slate-800 p-5 rounded-2xl gap-4">
                            
                            <!-- Infos Examen -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-blue-900/50 text-amber-400 border border-amber-500/20">
                                        {{ $exam->subject->name }}
                                    </span>
                                    <span class="text-xs text-slate-500">{{ $exam->created_at->format('d/m/Y') }}</span>
                                </div>
                                <h3 class="text-base font-bold text-white">{{ $exam->title }}</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    Caricato da: <strong class="text-amber-400">{{ $exam->uploader->name ?? 'Sconosciuto' }}</strong> 
                                    &bull; A.A. {{ $exam->academic_year }} &bull; {{ $exam->type }}
                                </p>
                            </div>

                            <!-- Actions Examen -->
                            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                                <a href="{{ route('exams.download', $exam->id) }}" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition text-center flex-1 md:flex-none">
                                    Scarica PDF
                                </a>
                                
                                <!-- Bouton Valider (Passe dans l'archive officielle) -->
                                <form method="POST" action="{{ route('admin.exams.make-official', $exam->id) }}" class="flex-1 md:flex-none">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition text-center shadow-lg shadow-emerald-600/20">
                                        Ufficializza
                                    </button>
                                </form>

                                <!-- Bouton Supprimer -->
                                <form method="POST" action="{{ route('exams.destroy', $exam->id) }}" class="flex-1 md:flex-none">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full bg-red-900/50 hover:bg-red-600 text-red-400 hover:text-white border border-red-900 text-xs font-bold px-4 py-2 rounded-xl transition text-center">
                                        Elimina
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-500">
                            Nessun nuovo documento inviato dalla community.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>