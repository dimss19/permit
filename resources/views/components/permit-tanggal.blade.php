@props(['permit'])

@php
    $mulai = $permit->tanggal_mulai ? $permit->tanggal_mulai->format('d/m/Y') : null;
    $selesai = $permit->tanggal_selesai ? $permit->tanggal_selesai->format('d/m/Y') : null;

    if ($mulai && $selesai) {
        $display = ($mulai === $selesai) ? $mulai : ($mulai . ' – ' . $selesai);
    } elseif ($mulai) {
        $display = $mulai;
    } elseif ($selesai) {
        $display = $selesai;
    } elseif ($permit->submitted_at) {
        $display = $permit->submitted_at->format('d/m/Y');
    } elseif ($permit->created_at) {
        $display = $permit->created_at->format('d/m/Y');
    } else {
        $display = '—';
    }
@endphp

<span class="text-gray-600 text-xs sm:text-sm font-medium whitespace-nowrap">
    {{ $display }}
</span>
