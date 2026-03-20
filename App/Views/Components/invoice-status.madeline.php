<div class="flex items-center gap-2">
    <?php
    $color = match($type ?? 'brouillon') {
        'valide' => 'green',
        'paye' => 'blue',
        'refuse' => 'red',
        'envoye' => 'brand',
        default => 'gray'
    };
    $label = match($type ?? 'brouillon') {
        'valide' => 'Validé',
        'paye' => 'Payé',
        'refuse' => 'Refusé',
        'envoye' => 'Envoyé',
        default => 'Brouillon'
    };
    ?>
    <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500 {{ $type === 'envoye' ? 'animate-pulse' : '' }}"></span>
    <span class="text-[10px] font-bold uppercase tracking-widest text-{{ $color }}-500/80">{{ $label }}</span>
</div>
