<p align="center"><img src="https://sekitarkita.id/images/sekitarkitalogo.png" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://github.com/ghuniyu/sekitarkita-backend/actions"><img src="https://github.com/ghuniyu/sekitarkita-backend/workflows/Production%20Server%20Deployment/badge.svg?branch=master&event=push" alt="Deploy Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Tentang SekitarKita.id

SekitarKita adalah aplikasi Berbasis Mobile yang membantu anda melakukan Tracking terhadap Titik Singgung anda dengan Orang-Orang terdekat dengan menggunakan Nearby Device Bluetooth. Aplikasi ini sangat berguna untuk melakukan pemetaan dalam pandemi COVID-19.
backend aplikasi dibangun Menggunakan [Laravel 7](https://laravel.com/) dan [Laravel Nova](https://nova.laravel.com/)


## Contributing
Proyek ini adalah Proyek Terbuka, siapapun dapat berkontribusi

## License
SekitarKita adalah open-sourced software yang berlisensi dibawah [MIT license](https://opensource.org/licenses/MIT).

# Dokumentasi API

### POST /store-device

### validation
```
'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
'nearby_device' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
'latitude' => 'nullable|numeric',
'longitude' => 'nullable|numeric',
'speed' => 'sometimes|nullable|numeric|min:0|max:100',
'device_name' => 'sometimes|nullable|string|max:100',
```

## POST /me

### validation
```
device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
```

## POST /store-firebase-token

### validation
```
'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
'firebase_token' => 'required|string|min:32|max:256'
```

## POST /device-history

### validation
```
'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/'
```

## POST set-health

### validation
```
'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
'health' => 'required|in:healthy,pdp,odp'
```
