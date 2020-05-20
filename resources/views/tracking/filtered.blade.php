<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Member Tracking</title>
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>

    <style>
        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: visible;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 1500ms infinite linear;
            -moz-animation: spinner 1500ms infinite linear;
            -ms-animation: spinner 1500ms infinite linear;
            -o-animation: spinner 1500ms infinite linear;
            animation: spinner 1500ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
<div id="loading" class="loading">Loading&#8230;</div>
<div id="map"></div>
<script src="{{ mix('js/app.js') }}"></script>
<script>
    const healthConditions = {
        healthy: 'Sehat',
        odp: 'ODP',
        pdp: 'PDP',
        confirmed: 'Positif'
    };

    function initMap() {
        let map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: {lat: -5.016889, lng: 115.268380},
            mapTypeId: 'terrain'
        });

        let oms = new OverlappingMarkerSpiderfier(map, {
            markersWontMove: true,   // we promise not to move any markers, allowing optimizations
            markersWontHide: true,   // we promise not to change visibility of any markers, allowing optimizations
            basicFormatEvents: true  // allow the library to skip calculating advanced formatting information
        });

        let iw = new google.maps.InfoWindow();

        fetch('/api/filteredTrack?area={{urldecode(request()->query('area'))}}&status={{urldecode(request()->query('status'))}}')
            .then((response) => {
                document.getElementById("loading").style.display = "none";
                return response.json();
            })
            .then((data) => {
                let markers = [];

                if (data.length === 0) {
                    Swal.fire({title: 'Error', text: 'data tidak tersedia', icon: 'error', heightAuto: false});
                    return
                }

                data.forEach(function (dot) {
                    let icon = {
                        url: dot.online ? `/images/markers/${dot.status}-online.gif` :
                            `/images/markers/${dot.status}.png`,
                        scaledSize: new google.maps.Size(50, 50),
                        labelOrigin: new google.maps.Point(25, 60)
                    };
                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng(dot.lat, dot.lng),
                        map: map,
                        label: dot.id,
                        icon: icon
                    });
                    markers.push(marker);
                    google.maps.event.addListener(marker, 'spider_click', function (e) {
                        const trackingLink = `/tracking?device_id=${dot.id}`;
                        const deviceLink = `/free-corona/resources/devices/${dot.id}`;
                        iw.setContent(`
                            <h4>Perangkat: <b><a style="text-decoration: underline" href='${deviceLink}'>${dot.id}</a></b></h4>
                            <h5>Status Kesehatan: ${healthConditions[dot.status]}</h5>
                            <div><a target="_blank" href='${trackingLink}'><b>Lihat Riwayat Perangkat</b></a></div>
                        `);
                        iw.open(map, marker);
                    });
                    oms.addMarker(marker);
                });

                const clusters = new MarkerClusterer(map, markers, {imagePath: '/images/markers/m'});
                map.setCenter(data[0]);
                smoothZoom(map, 15, map.getZoom());
                clusters.setMaxZoom(20);
            });

    }

    function smoothZoom(map, max, cnt) {
        if (cnt >= max) {
            return;
        } else {
            z = google.maps.event.addListener(map, 'zoom_changed', function (event) {
                google.maps.event.removeListener(z);
                smoothZoom(map, max, cnt + 1);
            });
            setTimeout(function () {
                map.setZoom(cnt)
            }, 80);
        }
    }

</script>
<script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOk60U75k2LegMxYYkT3xJPoRjeax9PmU&callback=initMap">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>
</body>
</html>
