@extends('mapping.base')
@section('title', 'Mapping Member -')
@push('js')
    <script type="text/javascript">
        @php
            $queryParam = request()->query('only');
            $queryParam = $queryParam ? "?only=$queryParam" : '';
        @endphp
        visualNetwork('/api/member-interaction{{ $queryParam }}', null, 0.3);
    </script>
@endpush
