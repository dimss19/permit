@props(['site' => null])

@if($site === 'Banyuwangi')
    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
        Banyuwangi
    </span>
@else
    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/70">
        {{ $site ?? 'Madiun' }}
    </span>
@endif
