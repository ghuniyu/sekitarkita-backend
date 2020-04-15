<?php

namespace App\Nova\Metrics;

use App\Models\Nearby;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class NewInteraction extends Value
{
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

        return $this->count($request, $nearby);
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
        return 'new-interaction';
    }
}
