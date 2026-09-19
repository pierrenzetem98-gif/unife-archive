@php
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
@endphp

<!-- BANDEROLE DÉFILANTE POUR INCITER À AJOUTER DES ÉPREUVES -->
<div class="fixed bottom-0 left-0 w-full z-50 overflow-hidden 
    {{ $isAdmin ? 'bg-amber-500/90 border-t-2 border-amber-300' : 'bg-blue-900/95 border-t-2 border-amber-500' }} 
    backdrop-blur-sm shadow-lg py-3">

    <div class="marquee-container overflow-hidden whitespace-nowrap">
        <div class="marquee-content inline-block animate-marquee">
            <!-- Message principal -->
            <span class="text-sm font-bold mx-4 {{ $isAdmin ? 'text-slate-950' : 'text-white' }}">
                📢 CONDIVIDI LE TUE PROVE D'ESAME!
            </span>
            
            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                • 
            </span>

            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                🎓 Hai sostenuto un esame? Aggiungi la prova alla banca dati per aiutare gli studenti del prossimo anno!
            </span>

            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                • 
            </span>

            <span class="text-sm font-semibold mx-4 {{ $isAdmin ? 'text-slate-950' : 'text-amber-400' }}">
                ⏳ Gennaio 2027 - Insieme costruiamo l'archivio UniFe!
            </span>

            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                • 
            </span>

            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                📤 Vai su "Aggiungi nuova prova" e carica il tuo PDF!
            </span>

            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                • 
            </span>

            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                🔄 Condividi con i tuoi colleghi! Più siamo, più ricco è l'archivio.
            </span>

            <span class="text-sm mx-4 {{ $isAdmin ? 'text-slate-800' : 'text-blue-200' }}">
                • 
            </span>

            <span class="text-sm font-bold mx-4 {{ $isAdmin ? 'text-slate-950' : 'text-white' }}">
                📢 CONDIVIDI LE TUE PROVE D'ESAME!
            </span>
        </div>
    </div>
</div>

<!-- STYLES POUR L'ANIMATION -->
<style>
    .marquee-container {
        width: 100%;
        overflow: hidden;
    }

    .marquee-content {
        display: inline-block;
        animation: marquee-scroll 25s linear infinite;
        white-space: nowrap;
    }

    @keyframes marquee-scroll {
        0% {
            transform: translateX(0%);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    /* PAUSE AU SURVOL */
    .marquee-container:hover .marquee-content {
        animation-play-state: paused;
    }
</style>