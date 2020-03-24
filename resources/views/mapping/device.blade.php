@extends('layouts.app')
@section('title', 'Mapping Device -')
@section('content')
    <div class="container-fluid">
       <div class="row">
           <div class="col-md-12 text-center">
              <h2> Data Mapping Device</h2>
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
        const options = {
            nodes: {
                shapeProperties: {
                    interpolation: false    // 'true' for intensive zooming
                }
            },
            layout: {improvedLayout: false},
            physics: {
                solver: 'forceAtlas2Based',
                timestep: 0.35,
                stabilization: {
                    enabled: true,
                    fit: true,
                    iterations: 1000,
                    updateInterval: 25
                }
            }
        };
        visualNetwork('/api/device-interaction', options);
    </script>
@endpush
