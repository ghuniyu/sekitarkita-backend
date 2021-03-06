<?php

namespace App\Http\Controllers\Api;

use App\Enums\ChangeRequestStatus;
use App\Enums\HealthStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\NearbyCollection;
use App\Models\ChangeRequest;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Nearby;
use App\Models\SelfCheck;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
        $valid['device_id'] = Str::lower($valid['device_id']);

        $key = Str::slug($valid['device_id']) . '_' . Str::slug($valid['nearby_device']);

        $scanned_device = cache()->remember($key, now()->addMinutes(10), function () use ($valid){
            $device = Device::updateOrCreate([
                'id' => Str::lower($valid['device_id'])
            ], [
                'id' => $valid['device_id'],
                'app_user' => true
            ]);

            abort_if($device->banned, 403, "Device ID ini di Banned");

            DeviceLog::create($valid);

            $scanned_device = Device::updateOrCreate([
                'id' => $valid['nearby_device']
            ], [
                'id' => $valid['nearby_device'],
                'device_name' => $valid['device_name'] ?? null
            ]);

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

            return $scanned_device;
        });

        return response()->json([
            'success' => true,
            'message' => 'Nearby Stored',
            'nearby_device' => [
                'id' => $scanned_device['id'],
                'name' => $scanned_device['name'] ?? null,
                'user_status' => $scanned_device['user_status'] ?? 'healthy',
                'created_at' => $scanned_device['created_at'],
                'updated_at' => $scanned_device['updated_at']
            ]
        ]);
    }

    public function getNearby(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/|exists:devices,id'
        ]);

        $valid['device_id'] = Str::lower($valid['device_id']);
        $device = Device::find($valid['device_id']);

        abort_if($device->banned, 403, "Device ID ini di Banned");

        $nearbies = Nearby::where('device_id', $device['id'])
            ->orWhere('another_device', $device['id'])
            ->withAppUser()
            ->paginate(25);

        return NearbyCollection::make($nearbies)->additional([
            'success' => true
        ]);
    }

    public function changeRequest(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/|exists:devices,id',
            'health' => 'required|in:healthy,pdp,odp,otg,positive,traveler',
            'phone' => 'sometimes|phone:ID',
            'nik' => 'sometimes|numeric|digits:16',
            'name' => 'sometimes|string',
        ]);

        abort_if(Device::find($valid['device_id'])->banned, 403, "Device ID ini di Banned");
        $valid['status'] = ChangeRequestStatus::PENDING;
        $valid['user_status'] = $valid['health'];

        /*if (isset($valid['nik']) && isset($valid['name'])) {
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
                            'message' => 'Anda sudah melakukan pengajuan sebelumnya, silahkan menunggu proses verifikasi'
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
                        'message' => 'nama tidak sesuai dengan KTP / kurang lengkap'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'gagal melakukan validasi data'
                ]);
            }
        }*/

        $hasCr = ChangeRequest::firstOrCreate([
            'device_id' => $valid['device_id'],
            'status' => ChangeRequestStatus::PENDING,
        ], $valid);

        if (!$hasCr->wasRecentlyCreated) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan pengajuan sebelumnya, silahkan menunggu proses verifikasi'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan anda akan segera diproses'
        ]);
    }

    public function storeFirebaseToken(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'firebase_token' => 'required|string|min:32|max:256'
        ]);

        $device = Device::updateOrCreate(
            ['id' => Str::lower($valid['device_id'])],
            [
                'app_user' => true,
                'id' => Str::lower($valid['device_id']),
                'firebase_token' => $valid['firebase_token'],
            ]
        );
        abort_if($device->banned, 403, "Device ID ini di Banned");
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

        $device = Device::find($valid['device_id']);
        abort_if($device->banned, 403, "Device ID ini di Banned");
        return response()->json([
            'id' => $device['id'],
            'name' => $device['name'],
            'user_status' => $device['user_status']
        ]);
    }

    public function track(Request $request, Device $device)
    {
        return DeviceLog::with('nearby')->where('device_id', $device['id'])->get()->map(function ($item) {
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

    public function filteredTracking(Request $request)
    {
        $valid = $this->validate($request, [
            'area' => 'required',
            'status' => 'required|in:healthy,pdp,odp,otg,positive,traveler,all',
        ]);
        $area = $valid['area'];
        $devices = Device::query();
        $devices->when($area = $valid['area'], function (Builder $query, $area) {
            if ($area == 'all') {
                return $query->whereNotNull('last_known_area');
            } else {
                return $query->where('last_known_area', 'like', "%$area%");
            }
        });
        $devices->when($valid['status'] !== 'all', function (Builder $query) use ($valid) {
            return $query->where('user_status', $valid['status']);
        });

        $devices = $devices->get();
        return $devices->map(function ($device) {
            return [
                'id' => $device['id'],
                'lat' => (float)$device['last_known_latitude'],
                'lng' => (float)$device['last_known_longitude'],
                'online' => $device['online'],
                'status' => $device['user_status']
            ];
        })->sortBy('online')->values();
    }

    public function storeSelfCheck(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'has_fever' => 'required|boolean',
            'has_flu' => 'required|boolean',
            'has_cough' => 'required|boolean',
            'has_breath_problem' => 'required|boolean',
            'has_sore_throat' => 'required|boolean',
            'has_in_infected_country' => 'required|boolean',
            'has_in_infected_city' => 'required|boolean',
            'has_direct_contact' => 'required|boolean',
            'name' => 'required|string',
            'phone' => 'required|string',
            'result' => ['required', 'string', new EnumValue(HealthStatus::class)]
        ]);
        $valid['device_id'] = Str::lower($valid['device_id']);

        $device = Device::updateOrCreate(
            ['id' => $valid['device_id']],
            [
                'app_user' => true,
                'name' => $valid['name'],
                'phone' => $valid['phone'],
            ]
        );
        abort_if($device->banned, 403, "Device ID ini di Banned");

        SelfCheck::create($valid);
        return response()->json([
            'success' => true,
            'message' => 'Self Check Stored'
        ]);
    }

    public function storeSelfCheckV2(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
            'has_fever' => 'required|boolean',
            'has_flu' => 'required|boolean',
            'has_cough' => 'required|boolean',
            'has_breath_problem' => 'required|boolean',
            'has_sore_throat' => 'required|boolean',
            'has_in_infected_country' => 'required|boolean',
            'has_in_infected_city' => 'required|boolean',
            'has_direct_contact' => 'required|boolean',
            'name' => 'required|string',
            'age' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'result' => ['required', 'string', new EnumValue(HealthStatus::class)]
        ]);
        $valid['device_id'] = Str::lower($valid['device_id']);

        $device = Device::updateOrCreate(
            ['id' => $valid['device_id']],
            [
                'app_user' => true,
                'name' => $valid['name'],
                'phone' => $valid['phone'],
                'age' => $valid['age'],
                'address' => $valid['address'],
            ]
        );

        abort_if($device->banned, 403, "Device ID ini di Banned");

        if ($device->wasRecentlyCreated || $valid['result'] != HealthStatus::HEALTHY) {

            ChangeRequest::firstOrCreate([
                'device_id' => $valid['device_id'],
                'status' => ChangeRequestStatus::PENDING,
            ], [
                'device_id' => $valid['device_id'],
                'user_status' => $valid['result'],
                'name' => $valid['name'],
                'phone' => $valid['phone']
            ]);
        }

        SelfCheck::create($valid);
        return response()->json([
            'success' => true,
            'message' => 'Self Check Stored'
        ]);
    }
}
