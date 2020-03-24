<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Support\Collection;

class MappingController extends Controller
{
    public function associatedInteraction()
    {

        $all = Device::with('nearbies')->get();
        $edges = new Collection();

        $nodes = $all->map(function ($n) {
            return [
                'id' => $n['id'],
                'label' => $n['label'] ?? $n['id'],
            ];
        });

        foreach ($all as $device) {
            if (count($device['nearbies']) > 0) {
                foreach ($device['nearbies'] as $nearby) {
                    $edges->push([
                        'from' => $device['id'],
                        'to' => $nearby['another_device']
                    ]);
                }
            }
        }
        return [
            'nodes' => $nodes->unique('id')->values(),
            'edges' => $edges,
        ];
    }

    public function recordedInteraction()
    {

        $all = Device::with('nearbies')->get();
        $edges = new Collection();

        $nodes = $all->map(function ($n) {
            return [
                'id' => $n['id'],
                'label' => $n['label'] ?? $n['id'],
            ];
        });

        foreach ($all as $device) {
            if (count($device['nearbies']) > 0) {
                foreach ($device['nearbies'] as $nearby) {
                    $nodes->push([
                        'id' => $nearby['another_device'],
                        'label' => $nearby['another_device']
                    ]);
                    $edges->push([
                        'from' => $device['id'],
                        'to' => $nearby['another_device']
                    ]);
                }
            }
        }
        return [
            'nodes' => $nodes->unique('id')->values(),
            'edges' => $edges,
        ];
    }
}
