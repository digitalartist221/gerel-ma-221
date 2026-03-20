@def('pageTitle')Gestion de l'Équipe — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in duration-700">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-12 border-b border-slate-100">
        <div class="space-y-4">
            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-brand-500 mb-2">Collaboration & Accès</p>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter">Votre Équipe<span class="text-brand-500">.</span></h1>
            <p class="text-slate-400 font-medium">Gérez les membres de votre organisation et leurs permissions.</p>
        </div>
        <a href="/users/nouveau" class="px-8 py-4 rounded-2xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-brand-600 transition-all shadow-xl shadow-black/10">+ Ajouter un membre</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @baat($users as $user)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 p-10 shadow-sm hover:shadow-xl hover:border-brand-100 transition-all">
            <div class="flex items-start justify-between mb-8">
                <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 font-black text-xl group-hover:bg-brand-50 group-hover:text-brand-500 transition-colors">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <span class="px-4 py-1.5 rounded-full bg-slate-50 text-slate-400 text-[9px] font-black uppercase tracking-widest border border-slate-100 group-hover:bg-brand-500 group-hover:text-white group-hover:border-brand-500 transition-all">
                    {{ $user->role ?: 'Membre' }}
                </span>
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-black text-slate-900 group-hover:text-brand-600 transition-colors">{{ $user->name }}</h3>
                <p class="text-xs text-slate-400 font-medium">{{ $user->email }}</p>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-50 flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity">
                 <a href="/users/edit/{{ $user->id }}" class="text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-500 transition-colors">Modifier profil</a>
                 <div class="flex gap-2">
                     <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                     <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">Actif</span>
                 </div>
            </div>
        </div>
        @jeexbaat
    </div>

    @ndax(empty($users))
    <div class="py-32 text-center flex flex-col items-center gap-6">
        <div class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200">
             <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Seul dans l'aventure ? Invitez vos collaborateurs.</p>
    </div>
    @jeexndax
</div>
@jeexdef
