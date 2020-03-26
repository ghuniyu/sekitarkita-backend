@extends('layouts.app')
@section('title', 'Mapping Member -')
@section('content')
    <div class="container-fluid">
        <div class="row">
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
            <div class="col-md-12 m-lg-5 text-center">
                <h5 class="mb-lg-2">Legend</h5>
                <br>
                <img src="/images/icons/smartphone_healthy.svg" style="max-height: 20px" alt=""><span class="mr-lg-4">Sehat</span>
                <img src="/images/icons/smartphone_odp.svg" style="max-height: 20px" alt=""><span class="mr-lg-4">ODP</span>
                <img src="/images/icons/smartphone_pdp.svg" style="max-height: 20px" alt=""><span class="mr-lg-4">PDP</span>
                <img src="/images/icons/smartphone_confirmed.svg" style="max-height: 20px" alt=""><span class="mr-lg-4">Positif</span>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        visualNetwork('/api/member-interaction?only=pdp', null, 1);
    </script>
@endpush
