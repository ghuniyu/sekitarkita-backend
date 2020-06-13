<html lang="id">
<head>

    <title>SIKM</title>
    <link rel="stylesheet" href="{{asset('/css/bootstrap4.css')}}">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col"><img src="{{asset('/images/sikm/kop-sikm.png')}}" class="w-100" alt="kop"><br><br>
            <div class="text-center"><h3><strong>SURAT IJIN MASUK</strong></h3><h5>No. 360/BPBD/{{$sikm['id']}}
                    /VI/2020</h5></div>
            <p><strong>Diberikan Kepada :</strong></p>
            <table class="w-100">
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{$sikm['nik']}}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{$sikm['name']}}</td>
                </tr>
                <tr>
                    <td>Daerah Asal</td>
                    <td>:</td>
                    <td>{{$sikm['originable']['name']}}</td>
                </tr>
                <tr>
                    <td>Daerah Tujuan</td>
                    <td>:</td>
                    <td>{{$sikm['destinationable']['name']}}</td>
                </tr>
                <tr>
                    <td>Kategori</td>
                    <td>:</td>
                    <td>{{\App\Enums\SIKMCategory::getDescription($sikm['category'])}}</td>
                </tr>
                <tr>
                    <td>Kode Perangkat</td>
                    <td>:</td>
                    <td>{{$sikm['device_id'] ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Masa berlaku</td>
                    <td>:</td>
                    <td>s.d {{$sikm['medical_issued']->addDays(3)->format('d M Y')}}</td>
                </tr>
            </table>
            <p><strong>Berdasarkan :</strong></p>
            <p>Hasil Pemeriksaaan Covid-19 <u>Non reaktif / Negative</u>, apabila keterangan yang diberikan <strong>PALSU</strong>,
                maka yang bersangkutan dikenakan <strong>SANKSI</strong> sesuai ketentuan perundang-undangan.</p></div>
    </div>
    <div class="justify-content-end row">
        <div class="text-center col-sm-3"><p>Gorontalo , {{$sikm->created_at->format('d M Y')}}</p><img class="w-100"
                                                                                                        src="{{asset('/images/sikm/ttd.png')}}"
                                                                                                        alt="ttd"
                                                                                                        style="margin-left: -55px;">
        </div>
    </div>
</div>
</body>
</html>
