<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Bandeau de synthèse du score -->
        <div class="bg-white border rounded-lg p-6 shadow mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Esito Simulazione: {{ $attempt->exam->title }}</h1>
            <p class="text-sm text-gray-500 mb-4">Completato il {{ $attempt->finished_at ? $attempt->finished_at->format('d/m/Y alle H:i') : '-' }}</p>
            
            <div class="inline-block px-8 py-4 rounded-xl {{ $attempt->score >= 18 ? 'bg-green-100 border border-green-300' : 'bg-red-100 border border-red-300' }}">
                <span class="block text-xs uppercase tracking-wider font-semibold {{ $attempt->score >= 18 ? 'text-green-700' : 'text-red-700' }}">
                    Punteggio Finale
                </span>
                <span class="text-4xl font-extrabold {{ $attempt->score >= 18 ? 'text-green-800' : 'text-red-800' }}">
                    {{ $attempt->score }} / 30
                </span>
            </div>

            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ route('subjects.show', $attempt->exam->subject_id) }}" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold py-2 px-5 rounded">
                    &larr; Torna alla Materia
                </a>
            </div>
        </div>

        <!-- Détail des réponses et corrections -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Revisione Dettagliata delle Domande</h2>
        <div class="space-y-4">
            @foreach($attempt->exam->questions as $index => $question)
                @php
                    $userAnswer = $attempt->answers->firstWhere('question_id', $question->id);
                    $selectedOptionId = $userAnswer ? $userAnswer->option_id : null;
                @endphp
                <div class="bg-white border rounded-lg p-5 shadow-sm">
                    <p class="font-semibold text-gray-900 mb-3">{{ $index + 1 }}. {{ $question->text }}</p>
                    
                    <div class="space-y-2">
                        @foreach($question->options as $option)
                            @php
                                $isChosen = $selectedOptionId === $option->id;
                                $isCorrect = $option->is_correct;
                                
                                $class = 'border-gray-200 bg-white text-gray-700';
                                if ($isCorrect) {
                                    $class = 'border-green-500 bg-green-50 text-green-900 font-semibold';
                                } elseif ($isChosen && !$isCorrect) {
                                    $class = 'border-red-500 bg-red-50 text-red-900 font-semibold';
                                }
                            @endphp
                            <div class="p-3 border rounded text-sm flex justify-between items-center {{ $class }}">
                                <span>{{ $option->text }}</span>
                                <div>
                                    @if($isCorrect)
                                        <span class="text-xs bg-green-200 text-green-800 px-2 py-0.5 rounded font-bold">Risposta Corretta</span>
                                    @endif
                                    @if($isChosen && !$isCorrect)
                                        <span class="text-xs bg-red-200 text-red-800 px-2 py-0.5 rounded font-bold">La tua scelta errata</span>
                                    @elseif($isChosen && $isCorrect)
                                        <span class="text-xs bg-green-600 text-white px-2 py-0.5 rounded font-bold">Corretto</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>