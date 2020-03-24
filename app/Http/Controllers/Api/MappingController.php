<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Support\Collection;

class MappingController extends Controller
{
    public function associatedInteraction()
    {

        $results = cache()->remember('associatedInteraction', now()->addHours(1), function () {
            $all = Device::with('nearbies')->get();
            $edges = new Collection();

            $nodes = $all->map(function ($n) {
                return [
                    'id' => $n['id'],
                    'label' => $n['label'] ?? $n['id'],
                    "font" => [
                        "size" => 16,
                        "multi" => "md",
                        "align" => "center"
                    ],
                    "shape" => "image",
                    "color" => "#97C2FC",
                    "image" => '/images/icons/smartphone_' . $n['health_condition'] . '.svg'
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
        });

        return response()->json($results);

    }

    public function recordedInteraction()
    {
        $results = cache()->remember('recordedInteraction', now()->addHours(1), function () {
            $all = Device::with('nearbies')->get();
            $edges = new Collection();

            $nodes = $all->map(function ($n) {
                return [
                    'id' => $n['id'],
                    'label' => $n['label'] ?? $n['id'],
                    "font" => [
                        "size" => 16,
                        "multi" => "md",
                        "align" => "center"
                    ],
                    "shape" => "image",
                    "color" => "#97C2FC",
                    "image" => '/images/icons/smartphone_' . $n['health_condition'] . '.svg'
                ];
            });

            foreach ($all as $device) {
                if (count($device['nearbies']) > 0) {
                    foreach ($device['nearbies'] as $nearby) {
                        $nodes->push([
                            'id' => $nearby['another_device'],
                            'label' => $nearby['another_device'],
                            "font" => [
                                "size" => 16,
                                "multi" => "md",
                                "align" => "center"
                            ],
                            "shape" => "image",
                            "color" => "#97C2FC",
                            "image" => '/images/icons/smartphone.svg'
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
        });

        return response()->json($results);
    }
}
