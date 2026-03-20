@use('layout')

@def('content')
<div class="py-12 space-y-12">
    <div class="flex justify-between items-center mb-12">
        <div>
            <h1 class="text-4xl font-black text-[#050510] tracking-tight mb-2">Votre Équipe</h1>
            <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Gérez vos collaborateurs et leurs accès.</p>
        </div>
        <a href="/equipe/nouveau" class="px-8 py-4 bg-[#050510] text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-2xl shadow-black/10 hover:scale-[1.05] transition-all inline-flex items-center gap-2">
            Ajouter un membre
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @ndax(empty($members))
            <div class="col-span-full p-12 md:p-24 text-center bg-gray-50 border border-dashed border-gray-200 rounded-[2rem] md:rounded-[3.5rem]">
                <p class="text-gray-400 font-bold uppercase tracking-widest text-[11px]">Aucun membre d'équipe pour le moment.</p>
            </div>
        @jeexndax

        @mboloo($members as $member)
            <div class="floating-card p-6 md:p-10 rounded-[2rem] md:rounded-[3.5rem] bg-white border border-slate-100 flex flex-col">
                <div class="w-20 h-20 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center text-2xl font-black mb-6 shadow-2xl shadow-brand-500/10">
                    {{ substr($member->name, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-[#050510] mb-1">{{ $member->name }}</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-6">{{ $member->email }}</p>
                
                <div class="px-4 py-1 bg-gray-50 rounded-full text-[9px] font-black uppercase tracking-widest text-gray-400 mb-8 border border-gray-100">
                    {{ $member->role }}
                </div>

                <div class="flex space-x-4 border-t border-gray-50 pt-8 w-full justify-center">
                    <a href="/equipe/edit/{{ $member->id }}" class="text-[10px] font-black uppercase tracking-widest text-brand-500 hover:scale-110 transition-transform">Modifier</a>
                </div>
            </div>
        @jeexmboloo
    </div>
</div>
@jeexdef
