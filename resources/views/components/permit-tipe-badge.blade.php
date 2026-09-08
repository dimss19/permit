@props(['tipe' => 'Internal'])

@if($tipe === 'Eksternal')
    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/70">
        Eksternal
    </span>
@else
    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-600">
        Internal
    </span>
@endif
