@indi('layout')

@def('pageTitle')Gestion des Entreprises — Gerel Ma Business@jeexdef

@def('content')
<div class="py-12">
    <div class="flex items-center justify-between mb-16">
        <div>
            <h1 class="text-5xl font-extrabold text-[#050510] tracking-tight mb-3">Vos Entreprises.</h1>
            <p class="text-gray-400 text-lg font-medium">Gérez vos différentes entités juridiques en un seul endroit.</p>
        </div>
        <a href="/entreprises/nouveau" class="px-8 py-4 rounded-full btn-dark text-sm font-bold shadow-2xl shadow-black/10">
            Ajouter une entité +
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @baat($entreprises as $entreprise)
        <div class="floating-card p-12 rounded-6xl group transition-all">
            <div class="flex items-start justify-between mb-8">
                <div class="w-16 h-16 rounded-3xl bg-gray-50 border border-gray-100 flex items-center justify-center text-3xl overflow-hidden group-hover:scale-105 transition-transform">
                    @ndax($entreprise->logo)
                        <img src="{{ $entreprise->logo }}" class="w-full h-full object-cover">
                    @jeexndax
                    @ndax(!$entreprise->logo)
                        {{ substr($entreprise->nom, 0, 1) }}
                    @jeexndax
                </div>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="/entreprises/edit/{{ $entreprise->id }}" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brand-500 transition-all border border-transparent hover:border-brand-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-[#050510] mb-2">{{ $entreprise->nom }}</h3>
            <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6">SIRET: {{ $entreprise->siret ?: 'N/A' }}</div>
            
            <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $entreprise->ville ?: 'Monde' }}</span>
                <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-full border-2 border-white bg-brand-100"></div>
                    <div class="w-6 h-6 rounded-full border-2 border-white bg-orange-100"></div>
                </div>
            </div>
        </div>
        @jeexbaat

        @ndax(empty($entreprises))
        <div class="col-span-full py-32 rounded-6xl border-2 border-dashed border-gray-100 flex flex-center">
            <div class="text-center w-full">
                <svg class="w-16 h-16 mx-auto mb-6 opacity-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <p class="text-gray-400 font-medium mb-8">Vous n'avez pas encore d'entreprise configurée.</p>
                <a href="/entreprises/nouveau" class="text-brand-500 font-black uppercase text-[10px] tracking-widest hover:underline">Créer la première entité —</a>
            </div>
        </div>
        @jeexndax
    </div>
</div>
@jeexdef
