<?php

namespace App\Nova\Metrics;

use App\Models\Device;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class NewDevice extends Value
{
    public $name = 'Device Baru';

    /**
     * Calculate the value of the metric.
     *
     * @param NovaRequest $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $device = Device::query();

        if ($area = $request->user()['area']) {
            $device->where('last_known_area', 'like', "%$area%");
        }

        return $this->count($request, $device);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'TODAY' => 'Hari Ini',
            7 => 'Minggu Ini'
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'new-device';
    }
}
