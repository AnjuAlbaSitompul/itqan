{{-- Cetak Option untuk Child saat ini --}}
{{-- Gunakan str_repeat untuk membuat indentasi spasi (&nbsp;) berdasarkan kedalaman (depth) --}}
<option value="{{ $child->id }}">
    {!! str_repeat('&nbsp;', $depth * 4) !!} <span class="text-muted">L{{ $depth }} -</span> {{ $child->name }}
</option>

{{-- Jika Child ini punya anak lagi (sub-unit), panggil kembali file ini secara rekursif --}}
@if($child->childrenRecursive && $child->childrenRecursive->isNotEmpty())
    @foreach($child->childrenRecursive as $subChild)
        @include('performance.kpireport.partials.org-unit-option', [
            'child' => $subChild,
            'depth' => $depth + 1
        ])
    @endforeach
@endif