<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('subjects.index', ['year' => $subject->year]) }}" class="text-blue-900 font-semibold hover:underline">&larr; Ritorna alle materie ({{ $subject->year }} Anno)</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $subject->name }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Liste des sujets PDF -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-xl font-bold text-gray-800">Prove d'esame disponibili ({{ $subject->exams->count() }})</h2>

            @forelse($subject->exams as $exam)
                <div class="bg-white p-5 rounded-lg border shadow-sm flex justify-between items-center">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $exam->type }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $exam->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Anno Accademico: {{ $exam->academic_year }} &bull; Caricato da: {{ $exam->uploader->name ?? 'Utente' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('exams.download', $exam->id) }}" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded shadow flex items-center gap-2">
                            <span>Scarica PDF</span>
                        </a>
                        @if(Auth::user()->isAdmin() || Auth::id() === $exam->created_by)
                            <form method="POST" action="{{ route('exams.destroy', $exam->id) }}" onsubmit="return confirm('Eliminare questa prova?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold">
                                    Elimina
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white p-6 rounded-lg border text-center text-gray-500">
                    Nessun documento PDF disponibile per questa materia. Sii il primo a caricarne uno!
                </div>
            @endforelse
        </div>

        <!-- Formulaire d'upload de PDF -->
        <div class="bg-white p-6 rounded-lg border shadow-sm h-fit">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Aggiungi nuovo esame</h2>
            <form method="POST" action="{{ route('exams.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase">Titolo</label>
                    <input type="text" name="title" placeholder="Es: Appello Gennaio 2026" required class="mt-1 w-full border-gray-300 rounded p-2 text-sm border">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase">Anno Accademico</label>
                    <input type="text" name="academic_year" placeholder="Es: 2025/2026" required class="mt-1 w-full border-gray-300 rounded p-2 text-sm border">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase">Tipologia</label>
                    <select name="type" class="mt-1 w-full border-gray-300 rounded p-2 text-sm border">
                        <option value="Esame Scritto">Esame Scritto</option>
                        <option value="Compitino">Compitino</option>
                        <option value="Prova in itinere">Prova in itinere</option>
                        <option value="Esercitazione">Esercitazione</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase">File PDF (max 10MB)</label>
                    <input type="file" name="pdf_file" accept="application/pdf" required class="mt-1 w-full text-sm border rounded p-1">
                </div>

                <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded text-sm transition">
                    Carica Documento
                </button>
            </form>
        </div>
    </div>
</x-app-layout>