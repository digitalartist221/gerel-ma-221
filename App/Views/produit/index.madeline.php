@indi('layout')

@def('pageTitle')Catalogue Produits — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Page Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-2">Inventaire & <span class="text-emerald-500">Catalogue.</span></h1>
            <p class="text-emerald-400 font-bold uppercase tracking-[0.2em] text-[10px]">Produits · Services · Tarification</p>
        </div>
        
        <div class="floating-deco -top-8 right-1/4 text-emerald-100 text-4xl">✧</div>
        
        <a href="/produits/nouveau" class="px-8 py-4 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl shadow-emerald-500/10 flex items-center gap-2">
            <span>Ajouter un Item</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </header>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @baat($produits as $produit)
        <div class="p-6 md:p-10 rounded-[2rem] md:rounded-[3.5rem] bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all group flex flex-col justify-between notebook-edge">
            <div>
                <div class="flex items-center justify-between mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 font-black italic shadow-sm group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        P
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $produit->stock > 5 ? 'bg-emerald-100 text-emerald-600' : 'bg-brand-100 text-brand-600' }}">
                        Fixe: {{ $produit->stock }}
                    </span>
                </div>
                
                <h3 class="text-lg font-black text-slate-900 tracking-tight leading-tight mb-2 truncate group-hover:text-emerald-600 transition-colors">{{ $produit->designation }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-10 pb-6 border-b border-slate-50">Secteur: Standard</p>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($produit->prix_unitaire, 0, ',', ' ') }}</p>
                    <p class="text-[10px] text-slate-400 font-bold">XOF / UNITÉ</p>
                </div>
                <a href="/produits/edit/{{ $produit->id }}" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 hover:text-slate-900 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </a>
            </div>
        </div>
        @jeexbaat

        @ndax(empty($produits))
        <div class="col-span-full py-32 text-center flex flex-col items-center gap-6">
            <div class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Rayonnages vides.</p>
        </div>
        @jeexndax
    </div>
</div>
@jeexdef
