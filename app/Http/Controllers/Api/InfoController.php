<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CallCenterResource;
use App\Http\Resources\HospitalResource;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Kecamatan;
use App\Models\Partner;
use App\Models\Provinsi;
use App\Nova\Kabupaten;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use App\Models\CallCenter;
use App\Models\Hospital;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class InfoController extends Controller
{
    const indonesiaCache = 'indonesia-statistics';
    const gorontaloCache = 'gorontalo-statistics';
    const provinceCache = 'province-statistics';

    public function getCallCenters()
    {
        return response()->json([
            'success' => true,
            'data' => CallCenterResource::collection(CallCenter::with(['area' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Kabupaten::class => [
                        'provinsi'
                    ],
                    Kecamatan::class => [
                        'kabupaten.provinsi'
                    ]
                ]);
            }])->get())
        ]);
    }

    public function getHospitals()
    {

        return response()->json([
            'success' => true,
            'data' => HospitalResource::collection(Hospital::with(['area' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Kabupaten::class => [
                        'provinsi'
                    ],
                    Kecamatan::class => [
                        'kabupaten.provinsi'
                    ]
                ]);
            }])->get())
        ]);
    }

    public function getPartners()
    {
        return response()->json([
            'success' => true,
            'data' => Partner::all()
        ]);
    }

    public function reportPartners(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'area' => 'nullable|string',
            'address' => 'nullable|string',
            'speed' => 'required|numeric',
        ]);
        $valid['device_id'] = Str::lower($valid['device_id']);

        if ($valid['area'] != null) {
            Device::updateOrCreate([
                'id' => $valid['device_id'],
            ], [
                'id' => $valid['device_id'] ?? null,
                'last_known_area' => $valid['area'] ?? null,
                'last_known_latitude' => $valid['latitude']  ?? null,
                'last_known_longitude' => $valid['longitude']  ?? null,
                'last_known_address' => $valid['address']  ?? null
            ]);
        }

        DeviceLog::create($valid);
        return response()->json([
            'success' => true,
            'message' => 'Partners Stored',
        ]);
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getIndonesiaStatistics()
    {
        try {
            if (Cache::has(self::indonesiaCache))
                return Cache::get(self::indonesiaCache);

            $response = Http::get(env('API_KWLCRN') . '/indonesia');

            if ($response->ok()) {
                $data = $response->json();
                Cache::put(self::indonesiaCache, $data, now()->addHours(2));
                return $data;
            }

            return $response->throw();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return array|Response|mixed
     * @throws Exception
     */
    public function getProvinceStatistics()
    {
        try {
            if (Cache::has(self::provinceCache))
                return Cache::get(self::provinceCache);

            $response = Http::get(env('API_KWLCRN') . '/indonesia/provinsi');

            if ($response->ok()) {
                $data = $response->json();
                Cache::put(self::provinceCache, $data, now()->addHours(2));
                return $data;
            }

            return $response->throw();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return array|Response|mixed
     * @throws Exception
     */
    public function getGorontaloStatistics()
    {
        try {
            if (Cache::has(self::gorontaloCache))
                return Cache::get(self::gorontaloCache);

            $response = Http::get(env('API_GTO') . '/api/virus-gorontalo');

            if ($response->ok()) {
                $data = $response->json();
                Cache::put(self::gorontaloCache, $data, now()->addHours(2));
                return $data;
            }

            return $response->throw();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
