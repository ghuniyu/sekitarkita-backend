@extends('mapping.base')
@section('title', 'Mapping Device - ')
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
