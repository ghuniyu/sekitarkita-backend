@extends('layouts.app')
@section('title', 'Mapping Member -')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2> Data Mapping Member</h2>
            </div>
            <div class="col-md-12">
                <div id="network"></div>

                <div id="networkLoadingBar">
                    <div class="networkOuterBorder">
                        <div id="networkText">0%</div>
                        <div id="networkBarBorder">
                            <div id="networkBar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
       visualNetwork('/api/member-interaction');
    </script>
@endpush
