<x-app-layout>
    @php
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $storicoExams = $subject->exams->where('is_community', false);
        $communityExams = $subject->exams->where('is_community', true);
    @endphp

    <div class="py-8 px-4 max-w-7xl mx-auto" x-data="{ tab: 'storico' }">

        <!-- Navigation Rapide -->
        <nav class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <a href="{{ route('subjects.index', ['year' => $subject->year]) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $isAdmin ? 'bg-slate-900 text-amber-400' : 'bg-white text-blue-900' }} border border-slate-300 dark:border-slate-800 shadow-sm transition">
                    &larr; Anno {{ $subject->year }}
                </a>
                <span class="text-slate-400">/</span>
                <span class="{{ $isAdmin ? 'text-amber-300 font-bold' : 'text-slate-900 dark:text-white font-bold' }}">{{ $subject->name }}</span>
            </div>

            <div class="flex items-center gap-3">
                <label for="sibling-jump" class="text-xs uppercase font-bold text-slate-500">Salta a:</label>
                <select id="sibling-jump" onchange="if(this.value) window.location.href=this.value" 
                        class="text-xs rounded-lg py-1.5 px-3 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                    <option value="">-- Altra materia (Anno {{ $subject->year }}) --</option>
                    @foreach($siblingSubjects as $sibling)
                        <option value="{{ route('subjects.show', $sibling->id) }}">{{ $sibling->name }}</option>
                    @endforeach
                </select>
            </div>
        </nav>

        <!-- Bandeau d'en-tête de la Matière -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border {{ $isAdmin ? 'border-amber-500/40' : 'border-slate-200 dark:border-slate-800' }} shadow-md mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-black tracking-widest uppercase mb-2 {{ $isAdmin ? 'bg-amber-500 text-slate-950' : 'bg-blue-950 text-amber-400' }}">
                    Anno {{ $subject->year }} &bull; Corso di Laurea in Informatica
                </span>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $subject->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $subject->description }}</p>
            </div>

            <a href="{{ route('subjects.zip', $subject->id) }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-extrabold text-sm py-3 px-5 rounded-xl shadow-lg transition">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                <span>Scarica Tutto (.ZIP)</span>
            </a>
        </div>

        <!-- Onglets : Archivio Storico / Community / Upload -->
        <div class="flex flex-wrap border-b border-slate-300 dark:border-slate-800 mb-6 gap-2">
            <button @click="tab = 'storico'" 
                    :class="tab === 'storico' ? 'border-b-2 border-amber-500 text-amber-500 font-bold' : 'text-slate-600 dark:text-slate-400'"
                    class="py-3 px-5 text-sm transition focus:outline-none flex items-center gap-2">
                <span>Archivio Storico (Ufficiale)</span>
                <span class="text-xs bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold px-2 py-0.5 rounded-full">
                    {{ $storicoExams->count() }}
                </span>
            </button>

            <button @click="tab = 'community'" 
                    :class="tab === 'community' ? 'border-b-2 border-amber-500 text-amber-500 font-bold' : 'text-slate-600 dark:text-slate-400'"
                    class="py-3 px-5 text-sm transition focus:outline-none flex items-center gap-2">
                <span>Nuove Aggiunte dalla Community</span>
                <span class="text-xs bg-amber-500/20 text-amber-400 border border-amber-500/30 font-bold px-2 py-0.5 rounded-full">
                    {{ $communityExams->count() }}
                </span>
            </button>

            <button @click="tab = 'upload'" 
                    :class="tab === 'upload' ? 'border-b-2 border-amber-500 text-amber-500 font-bold' : 'text-slate-600 dark:text-slate-400'"
                    class="py-3 px-5 text-sm transition focus:outline-none flex items-center gap-2 ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Carica Nuova Prova</span>
            </button>
        </div>

        <!-- CONTENU 1: ARCHIVIO STORICO -->
        <div x-show="tab === 'storico'" class="space-y-4">
            @forelse($storicoExams as $exam)
                <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-amber-400/50 transition">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-950 text-blue-900 dark:text-amber-400 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-200 dark:border-blue-900">
                            DOC
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase bg-blue-100 dark:bg-blue-950 text-blue-900 dark:text-blue-300">
                                    {{ $exam->type }}
                                </span>
                                <span class="text-xs text-slate-400 font-semibold">A.A. {{ $exam->academic_year }}</span>
                                <span class="text-[10px] uppercase font-bold text-slate-500">&bull; Archivio Ufficiale</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white mt-1">{{ $exam->title }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Caricato da: <strong>Pierre Nzetem (Admin)</strong></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('exams.download', $exam->id) }}" 
                           class="bg-blue-950 hover:bg-slate-800 text-amber-400 border border-amber-500/40 text-xs uppercase font-bold py-2.5 px-4 rounded-xl shadow-sm transition">
                            Scarica PDF
                        </a>
                        @if(Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('exams.destroy', $exam->id) }}" onsubmit="return confirm('Eliminare questa prova storica?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold p-2">Elimina</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 p-12 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 text-center text-slate-500 text-sm">
                    Nessun documento storico registrato.
                </div>
            @endforelse
        </div>

        <!-- CONTENU 2: COMMUNITY -->
        <div x-show="tab === 'community'" class="space-y-4">
            @forelse($communityExams as $exam)
                <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-amber-500/30 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-amber-400 transition">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0 border border-amber-500/30">
                            NEW
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase bg-amber-500/20 text-amber-300">
                                    {{ $exam->type }}
                                </span>
                                <span class="text-xs text-slate-400 font-semibold">A.A. {{ $exam->academic_year }}</span>
                                <span class="text-xs text-emerald-400 font-bold">&bull; Aggiunto il {{ $exam->created_at->format('d/m/Y') }}</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white mt-1">{{ $exam->title }}</h3>
                            <p class="text-xs text-slate-400 mt-1">
                                Contributo di: <strong class="text-amber-400">{{ $exam->uploader->name ?? 'Studente Anonimo' }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('exams.download', $exam->id) }}" 
                           class="bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs uppercase font-extrabold py-2.5 px-4 rounded-xl shadow transition">
                            Scarica PDF
                        </a>
                        @if(Auth::user()->isAdmin() || Auth::id() === $exam->created_by)
                            <form method="POST" action="{{ route('exams.destroy', $exam->id) }}" onsubmit="return confirm('Eliminare questo contributo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold p-2">Elimina</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 p-12 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 text-center text-slate-500 text-sm">
                    Nessuna prova recente inviata dagli studenti per questo insegnamento.
                </div>
            @endforelse
        </div>

        <!-- CONTENU 3: UPLOAD FORM -->
        <div x-show="tab === 'upload'" class="max-w-2xl bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-2">
                Condividi una nuova prova
            </h2>
            <p class="text-xs text-slate-400 mb-6">Il file caricato verrà immediatamente inserito nella sezione <strong>Nuove Aggiunte dalla Community</strong>.</p>

            <form method="POST" action="{{ route('exams.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Titolo dell'Esame</label>
                    <input type="text" name="title" placeholder="Es: Appello Straordinario Gennaio 2026" required 
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white p-3 text-sm focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Anno Accademico</label>
                        <input type="text" name="academic_year" placeholder="Es: 2025/2026" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white p-3 text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Tipologia</label>
                        <select name="type" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white p-3 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="Esame Scritto">Esame Scritto</option>
                            <option value="Compitino">Compitino</option>
                            <option value="Prova in itinere">Prova in itinere</option>
                            <option value="Esercitazione">Esercitazione</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">File PDF (Max 10MB)</label>
                    <input type="file" name="pdf_file" accept="application/pdf" required 
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-amber-500 file:text-slate-950 hover:file:bg-amber-400 cursor-pointer">
                </div>

                <button type="submit" 
                        class="w-full bg-blue-950 hover:bg-slate-800 text-amber-400 border border-amber-500 font-extrabold py-3 px-6 rounded-xl text-sm shadow-md transition">
                    Carica nell'Archivio Community
                </button>
            </form>
        </div>

    </div>
</x-app-layout>