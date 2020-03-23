<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Nearby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function store(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|max:64',
            'nearby_device' => 'required|string|max:64',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $valid['device_id'] = Str::lower($valid['device_id']);
        $valid['nearby_device'] = Str::lower($valid['nearby_device']);

        try {
            DB::beginTransaction();

            $device = Device::find($valid['device_id']);
            if (!$device) {
                Device::create([
                    'id' => $valid['device_id']
                ]);
            }

            $device = Device::find($valid['device_id']);
            Nearby::create([
                'device_id' => $device['id'],
                'another_device' => $valid['nearby_device'],
                'latitude' => $valid['latitude'] ?? null,
                'longitude' => $valid['longitude'] ?? null,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Nearby Stored'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => env('APP_ENV') == 'local' ? $e->getMessage() : 'duplicate',
                'stack_trace' => env('APP_ENV') == 'local' ? $e->getTraceAsString() : 'duplicate'
            ]);
        }
    }

    public function getNearby(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|max:64'
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
            'device_id' => 'required|string|max:64',
            'health' => 'required|in:healthy,pdp,odp'
        ]);

        $valid['device_id'] = Str::lower($valid['device_id']);

        $device = Device::find($valid['device_id']);
        if ($device) {
            $device['health_condition'] = $valid['health'];
            $device->save();

            return response()->json([
                'success' => true,
                'device' => $device
            ]);
        } else {
            Device::create([
                'id' => $valid['device_id'],
                'health_condition' => $valid['health']
            ]);

            return response()->json([
                'success' => true,
                'device' => Device::find($valid['device_id'])
            ]);
        }
    }

    public function storeFirebaseToken(Request $request)
    {
        $valid = $this->validate($request, [
            'device_id' => 'required|string|max:64',
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

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Firebase Token Stored'
            ]);
        } catch (\Exception $e) {
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
            'device_id' => 'required|string|max:64',
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
