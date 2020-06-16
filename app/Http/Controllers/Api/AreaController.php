<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    function getProvinces()
    {
        return Provinsi::all();
    }

    function getCities(Provinsi $province)
    {
        return $province->kabupatens->values();
    }

    function getDistricts(Kabupaten $city)
    {
        return $city->kecamatans->values();
    }

    function getVillages(Kecamatan $district)
    {
        return $district->kelurahans->values();
    }

    function getGorontalo()
    {
       $result = cache()->remember('get-area-gorontalo',  now()->addHours(12), function () {
           $data = Provinsi::with('kabupatens.kecamatans.kelurahans')->find(75);
           return [
               'data' => $data['kabupatens']->map(function ($kab) {
                   return $kab['kecamatans']->map(function ($kec) use ($kab) {
                       return $kec['kelurahans']->map(function ($des) use ($kab, $kec) {
                           return [
                               'id' => $des['id'],
                               'name' => sprintf('%s, %s, %s', $kab['name'], $kec['name'], $des['name'])
                           ];
                       });
                   });
               })->flatten(2),
               'success' => true
           ];
       });

       return response()->json($result);
    }

    function getOriginCities()
    {
        $result = cache()->remember('get-all-area', now()->addHours(12), function () {
            $data = Provinsi::with('kabupatens')->get();
            return [
                'data' => $data->map(function ($prov) {
                    return $prov['kabupatens']->map(function ($kab) use ($prov) {
                        return [
                            'id' => $kab['id'],
                            'name' => sprintf('%s, %s', $prov['name'], $kab['name'])
                        ];
                    });
                })->flatten(1),
                'success' => true
            ];
        });

        return response()->json($result);
    }
}
