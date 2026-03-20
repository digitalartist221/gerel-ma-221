@indi('layout')

@def('pageTitle')Gestion de la Waitlist — Gerel Ma@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in duration-700">
    <div class="flex justify-between items-end mb-16">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-4">Waitlist & Prospects.</h1>
            <p class="text-slate-400 font-medium">Capturez et gérez les visionnaires qui rejoignent l'aventure Gerel Ma.</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white px-6 py-4 rounded-3xl border border-slate-50 shadow-sm text-center">
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-300 mb-1">Total Inscrits</div>
                <div class="text-2xl font-black text-brand-600">{{ count($list) }}</div>
            </div>
        </div>
    </div>

    <div class="floating-card rounded-[3rem] overflow-hidden border-slate-50 shadow-[0_32px_64px_rgba(0,0,0,0.02)]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Date</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Prospect / Entreprise</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Email</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($list as $item)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-10 py-8 text-xs font-bold text-slate-400">
                        {{ date('d M Y', strtotime($item->created_at)) }}
                    </td>
                    <td class="px-10 py-8">
                        <div class="font-black text-slate-900">{{ $item->name ?: 'Anonyme' }}</div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-brand-500 mt-1">{{ $item->entreprise ?: 'Individuel' }}</div>
                    </td>
                    <td class="px-10 py-8 text-sm font-medium text-slate-600">
                        {{ $item->email }}
                    </td>
                    <td class="px-10 py-8 text-right">
                        <span class="px-4 py-1.5 rounded-full bg-orange-50 text-orange-500 text-[10px] font-black uppercase tracking-widest border border-orange-100">
                            {{ $item->statut }}
                        </span>
                    </td>
                </tr>
                @endforeach

                @if(empty($list))
                <tr>
                    <td colspan="4" class="px-10 py-24 text-center">
                        <div class="text-slate-300 italic text-sm">Aucun prospect enregistré pour le moment.</div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@jeexdef
