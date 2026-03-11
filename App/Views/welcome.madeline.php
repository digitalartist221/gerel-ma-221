@indi('layout')

@def('pageTitle')L'Art du PHP Moderne@jeexdef

@def('content')

    <!-- HERO SECTION IMMERSIVE -->
    <div class="relative min-h-[90vh] flex items-center justify-center px-6 pt-20 pb-32 overflow-hidden border-b border-white/[0.05]">
        
        <!-- Abstract Decoration -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-600/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute top-1/2 -right-24 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
            
            <!-- Grid Pattern -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-[10px] font-bold uppercase tracking-[0.3em] mb-12 animate-fade-in">
                Excellence Architecturale · PHP 8.3
            </div>

            <h1 class="text-7xl md:text-[9rem] font-black text-gray-900 dark:text-white tracking-tighter leading-[0.85] mb-10">
                L'Art du <br>
                <span class="font-serif italic font-light text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-gray-900 dark:via-white to-gray-500">Madeline</span>.
            </h1>

            <p class="text-xl md:text-2xl text-gray-500 dark:text-white/40 max-w-3xl mx-auto leading-relaxed mb-16 font-light">
                Le framework PHP de nouvelle génération qui fusionne vitesse extrême, 
                <span class="text-gray-900 dark:text-white font-medium italic">sémantique Wolof majestueuse</span> 
                et expérience développeur sans compromis.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="/docs" class="group relative w-full sm:w-auto px-12 py-5 bg-gray-900 dark:bg-white text-white dark:text-black font-black rounded-full transition-all hover:scale-105 active:scale-95 shadow-[0_20px_40px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_40px_rgba(255,255,255,0.1)] flex items-center justify-center gap-3">
                    Bâtir avec Madeline
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                
                <a href="/api/docs/ui" class="w-full sm:w-auto px-12 py-5 bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-bold rounded-full hover:bg-gray-200 dark:hover:bg-white/10 transition-all backdrop-blur-xl flex items-center justify-center gap-3 group">
                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-ping"></span>
                    Console API Interactive
                </a>
            </div>

            <!-- Dashboard Mockup/Visual -->
            <div class="mt-32 relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-brand-500 to-indigo-500 rounded-[2rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                <div class="relative bg-white dark:bg-[#08080c] border border-gray-200 dark:border-white/10 rounded-[2rem] p-4 lg:p-8 shadow-2xl overflow-hidden aspect-video lg:aspect-auto">
                    <div class="flex items-center gap-2 mb-6 border-b border-white/5 pb-4">
                        <div class="w-3 h-3 rounded-full bg-red-500/20 dark:bg-red-500/30"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/20 dark:bg-yellow-500/30"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/20 dark:bg-green-500/30"></div>
                        <div class="ml-4 px-3 py-1 bg-gray-100 dark:bg-white/5 rounded-lg text-[10px] font-mono text-gray-500 dark:text-white/40 tracking-wider">App/Models/User.php</div>
                    </div>
                    
                    <div class="grid lg:grid-cols-2 gap-12 text-left">
                        <div class="font-mono text-sm leading-relaxed space-y-1">
                            <p><span class="text-brand-500 dark:text-brand-400">class</span> <span class="text-amber-600 dark:text-amber-400 text-lg font-bold">User</span> <span class="text-brand-500 dark:text-brand-400">extends</span> <span class="text-indigo-600 dark:text-indigo-400 italic">MadelineORM</span> {</p>
                            <p class="text-gray-400 dark:text-white/20 pl-4">// Le système Zéro-Migration veille.</p>
                            <p class="pl-4"><span class="text-brand-500 dark:text-brand-400">public string</span> <span class="text-blue-600 dark:text-blue-300">$name</span>;</p>
                            <p class="pl-4"><span class="text-brand-500 dark:text-brand-400">public string</span> <span class="text-blue-600 dark:text-blue-300">$email</span>;</p>
                            <p class="pl-4"><span class="text-brand-500 dark:text-brand-400">public int</span> <span class="text-blue-600 dark:text-blue-300">$age</span>;</p>
                            <p class="text-white/10 pl-4 mt-4">// Vos colonnes MySQL s'auto-ajustent.</p>
                            <p><span class="text-gray-500">}</span></p>
                        </div>
                        <div class="hidden lg:block space-y-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Philosophie <br> <span class="font-serif italic font-light text-brand-600 dark:text-brand-400">Zéro-Migration</span>.</h3>
                            <p class="text-gray-500 dark:text-white/40 text-sm leading-relaxed">
                                Finies les galères de migrations SQL. Madeline lit vos propriétés PHP et altère vos tables MySQL en temps réel. 
                                <span class="text-brand-600 dark:text-brand-400 font-bold block mt-2 tracking-widest text-[10px] uppercase">Codé avec perfection.</span>
                            </p>
                            <div class="pt-6 grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5 group hover:border-brand-500/30 transition-colors">
                                    <div class="text-brand-600 dark:text-brand-400 font-black text-xl mb-1">->fari()</div>
                                    <div class="text-[9px] uppercase tracking-widest text-gray-400 dark:text-white/30">Select Wolof</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5 group hover:border-brand-500/30 transition-colors">
                                    <div class="text-indigo-600 dark:text-indigo-400 font-black text-xl mb-1">->bindu()</div>
                                    <div class="text-[9px] uppercase tracking-widest text-gray-400 dark:text-white/30">Insert Wolof</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURES GRID PREMIUM -->
    <section class="py-32 px-6 max-w-[1400px] mx-auto">
        <div class="text-center mb-24 space-y-4">
            <h2 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white tracking-tighter">Pourquoi <span class="font-serif italic font-light text-brand-600 dark:text-brand-400">Madeline</span> ?</h2>
            <p class="text-gray-500 dark:text-white/40 max-w-xl mx-auto text-lg leading-relaxed">
                Une ingénierie de pointe au service d'une syntaxe majestueuse.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Fast -->
            <div class="group p-10 rounded-[2.5rem] bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5 hover:bg-white dark:hover:bg-white/[0.04] transition-all hover:-translate-y-2 hover:shadow-xl dark:hover:shadow-none">
                <div class="w-16 h-16 bg-brand-500/10 rounded-2xl flex items-center justify-center text-brand-600 dark:text-brand-400 mb-8 border border-brand-500/20 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 tracking-tight">Vitesse Extrême</h3>
                <p class="text-gray-500 dark:text-white/40 leading-relaxed text-sm">
                    Routage synchrone et mode Turbo SPA natif. Aucun rechargement de page, seulement des transitions instantanées de 0.1ms.
                </p>
            </div>

            <!-- Secure -->
            <div class="group p-10 rounded-[2.5rem] bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5 hover:bg-white dark:hover:bg-white/[0.04] transition-all hover:-translate-y-2 hover:shadow-xl dark:hover:shadow-none">
                <div class="w-16 h-16 bg-brand-500/10 rounded-2xl flex items-center justify-center text-brand-600 dark:text-brand-400 mb-8 border border-brand-500/20 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 tracking-tight">Bouclier Natif</h3>
                <p class="text-gray-500 dark:text-white/40 leading-relaxed text-sm">
                    Protection anti-XSS et CSRF intégrées. Middlewares de sécurité HTTP configurés pour une tranquillité architecturale totale.
                </p>
            </div>

            <!-- Wolof -->
            <div class="group p-10 rounded-[2.5rem] bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5 hover:bg-white dark:hover:bg-white/[0.04] transition-all hover:-translate-y-2 hover:shadow-xl dark:hover:shadow-none">
                <div class="w-16 h-16 bg-brand-500/10 rounded-2xl flex items-center justify-center text-brand-600 dark:text-brand-400 mb-8 border border-brand-500/20 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.065M15 3a9 9 0 11-3.477 17.318/"></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 tracking-tight">Identité Wolof</h3>
                <p class="text-gray-500 dark:text-white/40 leading-relaxed text-sm">
                    La seule sémantique PHP au monde intégrant les terminologies Wolof pour une lisibilité et une proximité culturelle sans égale.
                </p>
            </div>
        </div>
    </section>

@jeexdef
