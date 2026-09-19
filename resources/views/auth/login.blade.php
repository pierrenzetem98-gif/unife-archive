<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 bg-slate-950 relative overflow-hidden" x-data="{ mode: 'login' }">
        
        <!-- Effets lumineux de fond (Bleu Nuit & Or) -->
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-blue-600/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-amber-500/15 blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">

            <!-- En-tête / Branding UniFe & Pierre Nzetem -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black tracking-widest uppercase bg-blue-900/60 text-amber-400 border border-amber-400/30 mb-3">
                    <span>UniFe</span>
                    <span>&bull;</span>
                    <span>Informatica</span>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">
                    UniFe <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-300">Archive</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">Piattaforma esclusiva per gli studenti di Informatica</p>

                <!-- Signature Instagram -->
                <div class="mt-2">
                    <a href="https://instagram.com/pierre.nzetem" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-400/90 hover:text-amber-300 transition">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <span>Curato da @pierre.nzetem</span>
                    </a>
                </div>
            </div>

            <!-- Conteneur Carte Formulaire -->
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">

                <!-- Sélecteur d'onglets (Accedi / Registrati) -->
                <div class="grid grid-cols-2 p-1 bg-slate-950/80 rounded-2xl border border-slate-800 mb-6">
                    <button type="button" @click="mode = 'login'"
                            :class="mode === 'login' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black shadow' : 'text-slate-400 font-bold hover:text-white'"
                            class="py-2 text-xs uppercase tracking-wider rounded-xl transition">
                        Accedi
                    </button>
                    <button type="button" @click="mode = 'register'"
                            :class="mode === 'register' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black shadow' : 'text-slate-400 font-bold hover:text-white'"
                            class="py-2 text-xs uppercase tracking-wider rounded-xl transition">
                        Registrati
                    </button>
                </div>

                <!-- Messages de statut de session -->
                <x-auth-session-status class="mb-4 text-xs font-semibold text-emerald-400" :status="session('status')" />

                <!-- ================= ONGLET 1 : CONNEXION ================= -->
                <div x-show="mode === 'login'">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="login_email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                                Email Universitaria o Personale
                            </label>
                            <input id="login_email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                   placeholder="nome.cognome@edu.unife.it"
                                   class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-950 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-400" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="login_password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                                Password
                            </label>
                            <input id="login_password" type="password" name="password" required autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-950 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-400" />
                        </div>

                        <!-- Remember me & Password dimenticata -->
                        <div class="flex items-center justify-between text-xs">
                            <label for="remember_me" class="inline-flex items-center text-slate-400 cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember" 
                                       class="rounded border-slate-700 bg-slate-950 text-amber-500 focus:ring-amber-500/50">
                                <span class="ms-2">Ricordami</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-amber-400 hover:text-amber-300 font-semibold transition">
                                    Password dimenticata?
                                </a>
                            @endif
                        </div>

                        <!-- Bouton Connexion -->
                        <button type="submit" 
                                class="w-full py-3 px-4 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-slate-950 shadow-lg shadow-amber-500/20 active:scale-95 transition">
                            Accedi al Portale
                        </button>
                    </form>
                </div>

                <!-- ================= ONGLET 2 : NOUVEL UTILISATEUR ================= -->
                <div x-show="mode === 'register'" x-cloak>
                    <div class="mb-4 p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-200 text-xs leading-relaxed">
                        💡 <strong>Nota:</strong> Dopo la registrazione, l'account sarà in attesa. Scrivi in DM a <strong>@pierre.nzetem</strong> su Instagram per l'attivazione immediata.
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Nom complet -->
                        <div>
                            <label for="reg_name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                                Nome e Cognome
                            </label>
                            <input id="reg_name" type="text" name="name" :value="old('name')" required autocomplete="name"
                                   placeholder="Mario Rossi"
                                   class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-950 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-400" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="reg_email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                                Email UniFe / Personale
                            </label>
                            <input id="reg_email" type="email" name="email" :value="old('email')" required autocomplete="email"
                                   placeholder="mario.rossi@edu.unife.it"
                                   class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-950 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-400" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="reg_password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                                Password
                            </label>
                            <input id="reg_password" type="password" name="password" required autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-950 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-400" />
                        </div>

                        <!-- Confirmation Password -->
                        <div>
                            <label for="reg_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                                Conferma Password
                            </label>
                            <input id="reg_password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-950 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-400" />
                        </div>

                        <!-- Bouton Enregistrement -->
                        <button type="submit" 
                                class="w-full py-3 px-4 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-slate-950 shadow-lg shadow-amber-500/20 active:scale-95 transition">
                            Crea Account e Richiedi Accesso
                        </button>
                    </form>
                </div>

            </div>

            <!-- Footer / Mention légale -->
            <p class="text-center text-[11px] text-slate-500 mt-6">
                UniFe Archive &bull; Dipartimento di Matematica e Informatica
            </p>
        </div>
    </div>
</x-guest-layout>