<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Nearby;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $valid['device_id'] = Str::lower($valid['device_id']);
        $valid['nearby_device'] = Str::lower($valid['nearby_device']);

        $device = Device::find($valid['device_id']);
        $nearby_device = Device::find($valid['nearby_device']);

        if (!$device) {
            $device = Device::create([
                'id' => $valid['device_id']
            ]);
        }

        $device->touch();
        try {
            DB::beginTransaction();

            Nearby::create([
                'device_id' => $device['id'],
                'another_device' => $valid['nearby_device'],
                'device_name' => $valid['device_name'] ?? null,
                'latitude' => $valid['latitude'] ?? null,
                'longitude' => $valid['longitude'] ?? null,
                'speed' => $valid['speed'] ?? null,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Nearby Stored',
                'nearby_device' => $nearby_device ?? null
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => true,
                'message' => env('APP_ENV') == 'local' ? $e->getMessage() : 'duplicate',
                'nearby_device' => $nearby_device ?? null,
                'stack_trace' => env('APP_ENV') == 'local' ? $e->getTraceAsString() : 'duplicate'
            ]);
        }
    }

    public function getNearby(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/'
        ]);

        $valid['device_id'] = Str::lower($valid['device_id']);

        $device = Device::find($valid['device_id']);
        if ($device) {
            return response()->json([
                'success' => true,
                'nearbies' => $device->load('nearbies')['nearbies']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unknown Device ID'
            ]);
        }
    }

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
            $device->update([
                'health_condition' => $valid['health'],
                'label'=> $valid['label'] ?? null,
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
                'label'=> $valid['label'] ?? null,
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

        $valid['device_id'] = Str::lower($valid['device_id']);

        try {
            DB::beginTransaction();

            $device = Device::find($valid['device_id']);
            if (!$device) {
                Device::create([
                    'id' => $valid['device_id']
                ]);
            }

            $device = Device::find($valid['device_id']);
            $device['firebase_token'] = $valid['firebase_token'];
            $device->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Firebase Token Stored'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => env('APP_ENV') == 'local' ? $e->getMessage() : 'duplicate',
                'stack_trace' => env('APP_ENV') == 'local' ? $e->getTraceAsString() : 'duplicate'
            ]);
        }
    }

    public function getMe(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|regex:/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
        ]);

        $valid['device_id'] = Str::lower($valid['device_id']);
        $device = Device::find($valid['device_id']);
        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'No Device ID Associated',
            ]);
        }
        return $device;
    }
}
