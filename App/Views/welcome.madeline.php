@indi('layout')

@def('pageTitle')Bâtissez l'Avenir de votre Entreprise@jeexdef

@def('content')
<div class="relative pt-20 pb-32">
    <!-- Hero Mesh Gradient Blob -->
    <div class="absolute top-0 right-0 -z-10 opacity-30 scale-150 rotate-12 blur-3xl wave-animation">
        <div class="w-[600px] h-[600px] bg-brand-200/50 rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 mb-32 relative z-10 w-full">
        <div class="flex flex-col lg:flex-row items-center gap-20">
            <!-- Left Content -->
            <div class="flex-1 text-left w-full">
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-brand-50 border border-brand-100 text-brand-600 text-[10px] font-black uppercase tracking-[0.2em] mb-10 shadow-sm">
                    Gerel Ma Business Suite
                </div>
                
                <h1 class="text-6xl md:text-[6.5rem] font-black text-slate-900 tracking-tighter leading-[0.9] mb-12">
                    Conçu pour les <br>
                    <span class="text-brand-500">entrepreneurs</span><br>
                    <span class="text-slate-300">africains.</span>
                </h1>
                
                <div class="flex flex-wrap items-center gap-4 mb-16">
                    <span class="px-6 py-3 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-widest flex items-center gap-3 shadow-xl shadow-black/10 notebook-edge">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Rapide & Minimal
                    </span>
                    <span class="px-6 py-3 rounded-full bg-slate-50 border border-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest flex items-center gap-3">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Sécurisé
                    </span>
                    <span class="px-6 py-3 rounded-full bg-amber-50 border border-amber-100 text-amber-600 text-xs font-black uppercase tracking-widest flex items-center gap-3">
                        🇸🇳 Made in Dakar
                    </span>
                </div>

                <div class="flex items-center gap-6">
                    <a href="#waitlist" class="px-12 py-6 rounded-full bg-brand-600 text-white text-sm font-black uppercase tracking-widest hover:bg-brand-500 transition-all shadow-2xl shadow-brand-500/20 inline-flex items-center gap-3">
                        Lancer l'expérience
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
            
            <!-- Right Graphic (GoalBucket abstract preview) -->
            <div class="flex-[0.8] relative w-full hidden lg:block">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-400/40 via-rose-400/20 to-amber-300/30 rounded-full blur-[100px] -z-10"></div>
                
                <div class="relative w-full rounded-[4rem] bg-white border border-slate-100 p-8 shadow-2xl shadow-slate-200/50 notebook-edge hover:-translate-y-4 transition-transform duration-700">
                    <div class="floating-deco -right-6 -top-6 text-brand-500/20 text-6xl">✦</div>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center">
                                    <div class="w-3 h-3 bg-brand-500 rounded-full"></div>
                                </div>
                                <div>
                                    <div class="h-2 w-20 bg-slate-200 rounded-full mb-1"></div>
                                    <div class="h-2 w-12 bg-slate-100 rounded-full"></div>
                                </div>
                            </div>
                            <div class="px-4 py-1.5 bg-emerald-50 text-emerald-500 rounded-full text-[9px] font-black uppercase">+12%</div>
                        </div>
                        
                        <div class="p-8 bg-slate-900 rounded-[2.5rem] text-white relative overflow-hidden group">
                            <div class="absolute -right-10 -top-10 w-32 h-32 bg-brand-500/30 rounded-full blur-2xl group-hover:bg-rose-500/30 transition-all"></div>
                            <div class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">Chiffre d'Affaires</div>
                            <div class="text-3xl font-black tracking-tighter mb-4">4 250 000 <span class="text-sm font-normal text-slate-500">XOF</span></div>
                            <div class="h-16 w-full mt-4 flex items-end gap-2">
                                <div class="w-full bg-white/10 rounded-t-lg h-[40%]"></div>
                                <div class="w-full bg-white/10 rounded-t-lg h-[60%]"></div>
                                <div class="w-full bg-brand-500 rounded-t-lg h-[100%]"></div>
                                <div class="w-full bg-white/10 rounded-t-lg h-[80%]"></div>
                                <div class="w-full bg-white/10 rounded-t-lg h-[50%]"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-6 bg-rose-50 rounded-3xl border border-rose-100 text-rose-600">
                                <div class="text-[9px] font-black uppercase tracking-widest mb-1">Factures Payées</div>
                                <div class="text-2xl font-black">24</div>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-slate-600">
                                <div class="text-[9px] font-black uppercase tracking-widest mb-1">Clients Actifs</div>
                                <div class="text-2xl font-black">156</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bento Features Section -->
    <section class="max-w-6xl mx-auto px-6 mb-32 z-10 relative">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="p-12 rounded-[3.5rem] bg-white border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-500 notebook-edge">
                <div class="w-20 h-20 rounded-[2.5rem] bg-brand-50 flex items-center justify-center text-brand-500 mb-8">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Facturation Éclair</h3>
                <p class="text-sm font-medium text-slate-400 leading-relaxed">Éditez devis et factures en quelques clics. Envoi HTML direct et statuts synchronisés en temps réel.</p>
            </div>
            
            <div class="p-12 rounded-[3.5rem] bg-slate-900 text-white shadow-2xl relative overflow-hidden group hover:-translate-y-4 transition-transform duration-500 delay-75 notebook-edge border border-slate-800">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-rose-500/20 rounded-full blur-3xl group-hover:bg-rose-500/30 transition-all"></div>
                <div class="w-20 h-20 rounded-[2.5rem] bg-rose-500/10 flex items-center justify-center text-rose-500 mb-8 border border-rose-500/20 relative z-10">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a9.015 9.015 0 015.524 1.889m-5.524-1.889a9.015 9.015 0 00-5.524 1.889"/></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-4 tracking-tight relative z-10">Analyse Fiscale</h3>
                <p class="text-sm font-medium text-slate-400 leading-relaxed relative z-10">Suivez vos charges critiques, maîtrisez votre assiette TVA et analysez la santé nette de votre entreprise.</p>
            </div>

            <div class="p-12 rounded-[3.5rem] bg-white border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-500 delay-150 notebook-edge">
                <div class="w-20 h-20 rounded-[2.5rem] bg-amber-50 flex items-center justify-center text-amber-500 mb-8">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Contrats Intelligents</h3>
                <p class="text-sm font-medium text-slate-400 leading-relaxed">Rédigez ou laissez agir notre éditeur pro. Signature sécurisée via un pad tactile intégré directement au navigateur.</p>
            </div>
        </div>
    </section>

    <!-- Waitlist Section -->
    <section id="waitlist" class="py-32 px-10">
        <div class="max-w-4xl mx-auto rounded-[4rem] bg-slate-900 p-16 md:p-24 text-center relative overflow-hidden group">
            <!-- Background Accent -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500/20 blur-[100px] rounded-full group-hover:bg-brand-500/30 transition-all duration-700"></div>
            
            <div class="relative z-10 space-y-10">
                <span class="px-5 py-2 rounded-full bg-brand-500/10 text-brand-400 text-[10px] font-black uppercase tracking-widest border border-brand-500/20">Accès Anticipé</span>
                <h2 class="text-5xl md:text-6xl font-black text-white tracking-tighter leading-none">
                    Rejoignez la révolution <br><span class="text-brand-500">Gerel Ma.</span>
                </h2>
                <p class="text-slate-400 text-lg max-w-xl mx-auto font-medium leading-relaxed">
                    Soyez parmi les premiers à piloter votre business avec la suite ERP la plus intuitive du marché. L'outil indispensable de votre croissance.
                </p>
                
                <form action="/api/waitlist/join" method="POST" class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4 pt-6">
                    <input type="email" name="email" required placeholder="Votre email..." class="bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white text-sm font-bold focus:bg-white/10 focus:ring-4 focus:ring-brand-500/20 outline-none transition-all">
                    <input type="text" name="entreprise" placeholder="Votre Entreprise..." class="bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white text-sm font-bold focus:bg-white/10 focus:ring-4 focus:ring-brand-500/20 outline-none transition-all">
                    <button type="submit" class="px-10 py-5 rounded-full bg-brand-600 text-white text-sm font-black uppercase tracking-widest hover:bg-brand-500 transition-all shadow-2xl shadow-brand-500/20">
                        Inscrivez-moi
                    </button>
                </form>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Zéro spam. Uniquement l'essentiel.</p>
            </div>
        </div>
    </section>

    <!-- Interface Preview (Simulation Image fournis) -->
    <div class="relative max-w-6xl mx-auto">
        <div class="floating-card rounded-6xl p-4 border-gray-100/50 shadow-[0_80px_120px_rgba(0,0,0,0.05)]">
            <div class="bg-white rounded-5xl overflow-hidden aspect-[16/9] relative border border-gray-50">
                <!-- Inner Dashboard Preview -->
                <div class="absolute inset-0 p-12 bg-gray-50/30">
                     <div class="grid grid-cols-2 gap-12 h-full">
                         <div class="space-y-8">
                             <div class="h-24 w-2/3 bg-white rounded-4xl shadow-sm border border-gray-50 p-6 flex items-center gap-6">
                                <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center">
                                    <div class="w-4 h-4 bg-brand-500 rounded-full"></div>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-2 w-32 bg-gray-100 rounded-full"></div>
                                    <div class="h-2 w-20 bg-gray-50 rounded-full"></div>
                                </div>
                             </div>
                             <div class="h-24 w-3/4 bg-white rounded-4xl shadow-sm border border-gray-50 p-6 flex items-center gap-6 translate-x-12">
                                <div class="w-12 h-12 rounded-2xl bg-brand-500 shadow-xl shadow-brand-500/30 flex items-center justify-center text-white font-black">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-2 w-40 bg-gray-100 rounded-full"></div>
                                    <div class="h-2 w-24 bg-gray-50 rounded-full"></div>
                                </div>
                             </div>
                         </div>
                         <div class="flex items-center justify-center">
                             <div class="text-center">
                                 <div class="text-8xl font-black text-gray-100 mb-4 tracking-tighter">SDK</div>
                                 <div class="text-xs font-black uppercase tracking-[0.4em] text-brand-500">Gerel Ma Ecosystem</div>
                             </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>
        
        <!-- Floating bubbles -->
        <div class="absolute -top-12 -left-12 w-48 floating-card p-6 rounded-4xl opacity-90 scale-110">
             <div class="text-[9px] font-black uppercase tracking-widest text-brand-500 mb-2">Statut Client</div>
             <div class="text-sm font-bold">12 Factures payées</div>
        </div>
        <div class="absolute -bottom-12 -right-12 w-48 floating-card p-6 rounded-4xl opacity-90 scale-110">
             <div class="text-[9px] font-black uppercase tracking-widest text-green-500 mb-2">Revenu cumulé</div>
             <div class="text-lg font-black">+4.2M FCFA</div>
        </div>
    </div>
</div>

<script>
@ndax(isset($_GET['success']))
    alert('Diadieuf! Votre inscription a été enregistrée.');
@jeexndax

document.querySelector('#waitlist form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button');
    const originalText = btn.innerText;
    
    btn.disabled = true;
    btn.innerText = 'Patientez...';
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form)
        });
        const result = await response.json();
        if (result.success) {
            form.innerHTML = `<div class="p-8 rounded-3xl bg-brand-500/10 border border-brand-500/20 text-brand-400 font-black uppercase tracking-widest animate-in zoom-in duration-500">${result.message}</div>`;
        } else {
            alert(result.message);
            btn.disabled = false;
            btn.innerText = originalText;
        }
    } catch (e) {
        alert('Une erreur est survenue. Veuillez réessayer.');
        btn.disabled = false;
        btn.innerText = originalText;
    }
});
</script>

<style>
@keyframes wave {
    0% { transform: scale(1.5) rotate(12deg) translate(0, 0); }
    50% { transform: scale(1.6) rotate(15deg) translate(10px, -20px); }
    100% { transform: scale(1.5) rotate(12deg) translate(0, 0); }
}
.wave-animation { animation: wave 10s ease-in-out infinite; }
</style>
@jeexdef
