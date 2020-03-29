<?php

namespace App\Http\Resources;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CallCenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
            'phone' => $this['phone'],
            'mobile' => $this['mobile'],
            'website' => $this['website'],
            'area' => $this['area']['nama'],
            'area_detail' => $area,
        ];
    }
}
