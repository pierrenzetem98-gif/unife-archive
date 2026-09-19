@php
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
@endphp

<header class="{{ $isAdmin ? 'bg-slate-950 border-b border-amber-500/40 text-white' : 'bg-slate-900 border-b border-blue-800 text-white' }} shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <!-- Logo UniFe & Branding -->
            <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-xl {{ $isAdmin ? 'bg-gradient-to-tr from-amber-600 to-yellow-400 text-slate-950' : 'bg-gradient-to-tr from-blue-700 to-amber-400 text-white' }} flex items-center justify-center font-black text-xl shadow-lg group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-wider block leading-none text-white">UNIFE <span class="text-amber-400">ARCHIVE</span></span>
                    <span class="text-[10px] tracking-widest text-slate-400 uppercase font-semibold">Corso di Laurea in Informatica</span>
                </div>
            </a>

            <!-- User Menu, Status & Instagram -->
            <div class="flex items-center gap-3 flex-wrap">

                <!-- Bouton Admin : Approvazioni -->
                @if($isAdmin)
                    <a href="{{ route('admin.approvals.index') }}"
                       class="inline-flex items-center gap-1.5 bg-amber-500 text-slate-950 text-xs px-3 py-1.5 rounded-full font-bold shadow hover:bg-amber-400 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Approvazioni</span>
                    </a>
                @endif

                <!-- Lien Instagram Mis en Valeur -->
                <a href="https://instagram.com/pierre.nzetem" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow hover:scale-105 transition transform">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    <span>@pierre.nzetem</span>
                </a>

                <!-- Indicateur de Rôle -->
                @if($isAdmin)
                    <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/40 text-amber-300 text-xs px-3 py-1 rounded-full font-bold">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        Admin Workspace
                    </span>
                @else
                    <span class="inline-flex items-center bg-blue-800 text-blue-200 text-xs px-3 py-1 rounded-full font-semibold border border-blue-700">
                        Student Pass
                    </span>
                @endif

                <span class="text-sm font-semibold text-slate-200 hidden md:block">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500/20 hover:bg-red-600 border border-red-500/30 text-red-200 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                        Esci
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>