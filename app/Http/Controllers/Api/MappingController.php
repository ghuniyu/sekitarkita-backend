<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Nearby;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MappingController extends Controller
{
    public function associatedInteraction(Request $request)
    {
        $queryParam = $request->query('only') ?? 'all';
        $key = "associatedInteraction_{$queryParam}_{$request->getHost()}";

        $results = cache()->remember($key, now()->addHours(1), function () use ($request) {
            $devices = Device::query();
            $devices->when($this->checkPartner($request->getHost()), function ($query) use ($request) {
                return $query->where('last_known_area', 'like', "%{$request->user()['area']}%");
            });

            $devices = $devices->get();
            if ($request->get('only')) {
                $filtered = $devices->where('health_condition', $request->get('only'));
                $known_nearby = Nearby::whereIn('device_id', $filtered->pluck('id'))->get();
            } else {
                $filtered = $devices;
                $known_nearby = Nearby::whereIn('another_device', $filtered->pluck('id'))->get();
            }

            $nodes = new Collection();
            $edges = new Collection();

            foreach ($filtered as $device) {
                $nodes->push([
                    'id' => $device['id'],
                    'label' => $device['id'],
                    "font" => [
                        "size" => 16,
                        "multi" => "md",
                        "align" => "center"
                    ],
                    "shape" => "image",
                    "color" => "#97C2FC",
                    "image" => '/images/icons/smartphone_' . $device['health_condition'] . '.svg'
                ]);
            }

            foreach ($known_nearby as $data) {
                $health = $devices->firstWhere('id', $data['another_device'])['health_condition'];
                $nodes->push([
                    'id' => $data['another_device'],
                    'label' => $data['another_device'],
                    "font" => [
                        "size" => 16,
                        "multi" => "md",
                        "align" => "center"
                    ],
                    "shape" => "image",
                    "color" => "#97C2FC",
                    "image" => $health ? '/images/icons/smartphone_' . $health . '.svg' : '/images/icons/smartphone.svg'
                ]);

                $edges->push([
                    'from' => $data['device_id'],
                    'to' => $data['another_device']
                ]);
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


    private function checkPartner($host) {
        return $host !== env('APP_DOMAIN','sekitarkita.id') && $host !== 'localhost';
    }
}
