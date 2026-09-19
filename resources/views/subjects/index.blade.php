<x-app-layout>
    @php
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
    @endphp

    <div class="py-10 px-4 max-w-7xl mx-auto space-y-12">

        <!-- BANDEAU PRINCIPAL HERO (Bleu Nuit & Or) -->
        <div class="relative overflow-hidden rounded-3xl {{ $isAdmin ? 'bg-slate-950 border-2 border-amber-500/50 shadow-amber-500/10' : 'bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 border border-blue-800/60 shadow-blue-950/40' }} shadow-2xl p-8 sm:p-12 text-white">
            <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
            <div class="absolute left-1/2 -top-20 w-96 h-96 rounded-full bg-blue-600/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="text-center lg:text-left space-y-3 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black tracking-widest uppercase {{ $isAdmin ? 'bg-amber-500 text-slate-950' : 'bg-blue-900 text-amber-400 border border-amber-400/30' }}">
                        <span>Università degli Studi di Ferrara</span>
                        <span>&bull;</span>
                        <span>Informatica</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white leading-tight">
                        Archivio Esami <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-300">Completo</span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                        Tutte le prove d'esame, parziali ed esercitazioni in formato PDF, organizzate per anno accademico e materia.
                    </p>

                    <!-- Signature Instagram Créateur -->
                    <div class="pt-2 flex items-center justify-center lg:justify-start gap-2">
                        <span class="text-xs text-slate-400">Piattaforma curata da:</span>
                        <a href="https://instagram.com/pierre.nzetem" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 hover:scale-105 transition-transform text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span>@pierre.nzetem</span>
                        </a>
                    </div>
                </div>

                <!-- TÉLÉCHARGEMENT TOTAL ZIP -->
                <div class="flex flex-col items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('archive.full.zip') }}" 
                       class="group relative inline-flex items-center justify-center gap-3 w-full sm:w-auto px-8 py-5 rounded-2xl font-black text-sm uppercase tracking-wider bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 text-slate-950 shadow-xl shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-[1.03] active:scale-[0.98] transition-all duration-200">
                        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Scarica Tutto l'Archivio (.ZIP)</span>
                    </a>
                    <span class="text-[11px] uppercase tracking-widest text-slate-400 font-semibold">
                        Tutti gli anni (I, II, III) &bull; Compressione istantanea
                    </span>
                </div>
            </div>
        </div>

        <!-- NAVIGATION PAR ANNÉE & ACTIONS -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white/50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 backdrop-blur-sm">
            <div class="flex flex-wrap items-center gap-2">
                @foreach(['I' => 'Primo Anno', 'II' => 'Secondo Anno', 'III' => 'Terzo Anno'] as $yearKey => $yearLabel)
                    <a href="{{ route('subjects.index', ['year' => $yearKey]) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all {{ $selectedYear === $yearKey ? ($isAdmin ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20 ring-2 ring-amber-400' : 'bg-blue-950 text-amber-400 shadow-lg shadow-blue-950/30 ring-2 ring-amber-500/50') : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                        <span class="w-2 h-2 rounded-full {{ $selectedYear === $yearKey ? 'bg-amber-400' : 'bg-slate-400' }}"></span>
                        <span>{{ $yearLabel }}</span>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('years.zip', $selectedYear) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 hover:bg-amber-500/10 transition border border-amber-500/30">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                <span>Scarica solo Anno {{ $selectedYear }} (.ZIP)</span>
            </a>
        </div>

        <!-- GRILLE DES MATIÈRES REDESIGNÉE -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($subjects as $subject)
                <div class="group relative flex flex-col justify-between rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-2xl hover:border-amber-400/50 dark:hover:border-amber-500/40 transition-all duration-300">
                    
                    <!-- Ligne dorée supérieure subtile à l'état hover -->
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-700 via-amber-400 to-yellow-400 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>

                    <div>
                        <!-- En-tête de carte -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-900 to-slate-900 text-amber-400 flex items-center justify-center font-black text-sm shadow-md border border-amber-500/20">
                                    {{ substr($subject->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Anno Accademico</span>
                                    <span class="text-xs font-extrabold text-blue-900 dark:text-amber-400">Anno {{ $subject->year }}</span>
                                </div>
                            </div>

                            <!-- Badge compteur de PDF -->
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $subject->exams_count > 0 ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $subject->exams_count }} PDF</span>
                            </span>
                        </div>

                        <!-- Titre de la matière -->
                        <h3 class="text-lg font-black text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors leading-snug">
                            {{ $subject->name }}
                        </h3>

                        <!-- Description abrégée -->
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                            {{ $subject->description ?: 'Nessuna descrizione disponibile per questo insegnamento.' }}
                        </p>
                    </div>

                    <!-- Pied de carte : Action -->
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-3">
                        <span class="text-[11px] text-slate-400 font-medium">Archivio esami</span>
                        <a href="{{ route('subjects.show', $subject->id) }}" 
                           class="inline-flex items-center gap-2 py-2 px-4 rounded-xl text-xs font-black uppercase tracking-wider bg-blue-950 dark:bg-slate-800 text-amber-400 border border-amber-500/30 group-hover:bg-amber-500 group-hover:text-slate-950 group-hover:border-transparent transition-all shadow-sm">
                            <span>Accedi</span>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-slate-900 rounded-2xl p-12 text-center text-slate-400 border border-slate-200 dark:border-slate-800">
                    Nessuna materia presente per questa selezione.
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>