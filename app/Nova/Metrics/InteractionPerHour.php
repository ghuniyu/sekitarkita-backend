<?php

namespace App\Nova\Metrics;

use App\Models\Nearby;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class InteractionPerHour extends Trend
{
    public $name = 'Interaksi Per Jam';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $nearby = Nearby::query();

        if ($area = $request->user()['area']) {
            $nearby->whereHas('device', function ($q) use ($area) {
                return $q->where('last_known_area', 'like', "%$area%");
            });
        }
        return $this->countByHours($request, $nearby);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            12 => '12 Jam',
            24 => '24 Jam',
            48 => '48 Jam',
            96 => '96 Jam',
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
        return 'interaction-per-hour';
    }
}
