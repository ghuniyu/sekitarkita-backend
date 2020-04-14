<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChangeRequest;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Nearby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function store(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'nearby_device' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'speed' => 'sometimes|nullable|numeric|min:0|max:100',
            'device_name' => 'sometimes|nullable|string|max:100',
        ]);

        $valid['nearby_device'] = Str::lower($valid['nearby_device']);
        $device = Device::firstOrCreate([
            'id' => Str::lower($valid['device_id'])
        ], $valid);

        DeviceLog::create($valid);

        $nearby_device = Device::find($valid['nearby_device']);
        $device->touch();

        Nearby::updateOrCreate([
            'device_id' => $device['id'],
            'another_device' => $valid['nearby_device'],
        ], [
            'device_id' => $device['id'],
            'another_device' => $valid['nearby_device'],
            'device_name' => $valid['device_name'] ?? null,
            'latitude' => $valid['latitude'] ?? null,
            'longitude' => $valid['longitude'] ?? null,
            'speed' => $valid['speed'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nearby Stored',
            'nearby_device' => $nearby_device ?? null
        ]);
    }

    public function getNearby(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/|exists:devices,id'
        ]);

        $valid['device_id'] = Str::lower($valid['device_id']);

        $device = Device::find($valid['device_id']);
        return response()->json([
            'success' => true,
            'nearbies' => $device->load('nearbies')['nearbies']
        ]);
    }

    public function changeRequest(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/|exists:devices,id',
            'health' => 'required|in:healthy,pdp,odp,confirmed,odr',
            'phone' => 'sometimes|phone:ID',
            'nik' => 'sometimes|numeric|size:16',
            'name' => 'sometimes|string',
        ]);
        $valid['status'] = 'pending';

        if ($valid['nik'] && $valid['name']) {
            $response = Http::withBasicAuth(env('CHECKER_KEY'), env('CHECKER_VALUE'))
                ->post(env('CHECKER_URL'), [
                    'nik' => $valid['nik'],
                    'name' => $valid['name']
                ]);

            if ($response->ok()) {
                $content = $response->json();
                if ($content['message'] == 'valid' && $content['data'] > 60) {
                    $hasCr = ChangeRequest::firstOrCreate([
                        'device_id' => $valid['device_id'],
                        'status' => 'pending',
                    ], $valid);
                    if (!$hasCr->wasRecentlyCreated) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Silahkan tunggu pengajuan anda yang sebelumnya diproses'
                        ]);
                    } else {
                        return response()->json([
                            'success' => true,
                            'message' => 'Pengajuan anda akan segera diproses'
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'nama tidak sesuai dengan KTP'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'gagal melakukan validasi data'
                ]);
            }
        }

        ChangeRequest::create($valid);
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan anda akan segera diproses'
        ]);
    }

//    TODO : Going to Deprecated soon
    public function setHealth(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'health' => 'required|in:healthy,pdp,odp,confirmed,odr',
            'label' => 'sometimes|nullable|string',
            'phone' => 'sometimes|phone:ID'
        ]);

        $valid['device_id'] = Str::lower($valid['device_id']);

        $device = Device::find($valid['device_id']);
        if ($device) {
            $device->touch();
            $device->update([
                'health_condition' => $valid['health'],
                'label' => $valid['label'] ?? null,
                'phone' => $valid['phone'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'device' => $device,
            ]);
        } else {
            $device = Device::create([
                'id' => $valid['device_id'],
                'health_condition' => $valid['health'],
                'label' => $valid['label'] ?? null,
                'phone' => $valid['phone'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'device' => $device
            ]);
        }
    }

    public function storeFirebaseToken(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'firebase_token' => 'required|string|min:32|max:256'
        ]);

        $device = Device::firstOrCreate(['id' => Str::lower($valid['device_id'])]);
        if (!$device->wasRecentlyCreated) {
            $device['firebase_token'] = $valid['firebase_token'];
            $device->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Firebase Token Stored'
        ]);
    }

    public function getMe(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/|exists:devices,id',
        ]);
        return Device::find($valid['device_id']);
    }

    public function track(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/|exists:devices,id',
        ]);
        return DeviceLog::with('nearby')->where('device_id', $valid['device_id'])->get()->map(function ($item) {
            return [
                'lat' => (float)$item['latitude'],
                'lng' => (float)$item['longitude'],
                'nearby' => $item['nearby_device'],
                'created_at' => $item['created_at'],
                'nearby_info' => $item['nearby']
            ];
        })->unique(function ($item) {
            return $item['lat'] . $item['lng'] . $item['nearby'];
        })->values()->groupBy(function ($item) {
            return $item['lat'] . ',' . $item['lng'];
        })->map(function ($v, $k) {
            return [
                "lat" => (float)explode(',', $k)[0],
                "lng" => (float)explode(',', $k)[1],
                "nearby" => collect($v)->map(function ($i) {
                    return $i['nearby'] . ' - ' . $i['created_at'];
                })
            ];
        })->values();
    }
}
