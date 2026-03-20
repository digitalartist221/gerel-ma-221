<div class="flex items-center gap-4">
    <?php if(!empty($logo)): ?>
        <img src="{{ $logo }}" alt="{{ $nom }}" class="w-12 h-12 rounded-xl object-cover shadow-lg border border-white/5">
    <?php else: ?>
        <div class="w-12 h-12 rounded-xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center text-brand-500 font-black">
            {{ substr($nom ?? 'M', 0, 1) }}
        </div>
    <?php endif; ?>
    <div>
        <div class="text-sm font-bold text-white">{{ $nom ?? 'Entreprise sans nom' }}</div>
        <div class="text-[9px] text-gray-500 uppercase tracking-widest font-light">{{ $siret ?? 'SIRET non renseigné' }}</div>
    </div>
</div>
