<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sekitar</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .info {
                text-align: justify;
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
                max-width: 50%;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    SekitarKita
                </div>

                <center>
                    <div class="info">
                        Aplikasi SekitarKita membantu Anda melacak apakah Anda tergolong Orang Dalam Pengawasan (ODP) berdasarkan history data interaksi anda dengan orang lain.
                        <br><br>
                        Aplikasi ini akan menyalakan Bluetooth Anda setiap saat dan menyimpan data perangkat lain disekitar Anda.
                        <br><br>
                        Ketika terdapat Pasien Dalam Pengawasan (PDP) yang melaporkan terinfeksi virus Covid-19 dan memiliki riwayat bersinggungan dengan Anda, maka sistem akan memberikan informasi tersebut kepada Anda.
                        <br><br>
                        Begitu pula jika Anda terinfeksi Covid-19, Anda dapat membagikan informasi ke seluruh potensial ODP berdasarkan riwayat perangkat sekitar yang telah tersimpan.
                        <br><br>
                        Data pribadi Anda tidak akan ditampilkan. Karena aplikasi ini tidak meminta Anda mengisi data pribadi apapun.
                        <br><br>
                        Mari jadi bagian untuk Solusi Pencegahan Covid-19!
                    </div>
                </center>
            </div>
        </div>
    </body>
</html>
