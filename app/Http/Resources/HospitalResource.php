<?php

namespace App\Http\Resources;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class HospitalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this['area'] instanceof Kecamatan) {
            $area = sprintf("Kecamatan %s, Kabupaten %s, Provinsi %s", Str::title(data_get($this['area'], 'nama')), Str::title(data_get($this['area'], 'kabupaten.nama')), Str::title(data_get($this['area'], 'kabupaten.provinsi.nama')));
        } elseif ($this['area'] instanceof Kabupaten) {
            $area = sprintf("Kabupaten %s, Provinsi %s", Str::title(data_get($this['area'], 'nama')), Str::title(data_get($this['area'], 'provinsi.nama')));
        } else {
            $area = sprintf("Provinsi %s", Str::title(data_get($this['area'], 'nama')));
        }

        return [
            'name' => $this['name'],
            'address' => $this['address'],
            'phone' => $this['phone'],
            'area_detail' => $area,
            'area' => $this['area']['nama'],
            'latitude' => $this['latitude'],
            'longitude' => $this['longitude'],
        ];
    }
}
