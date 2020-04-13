<?php

namespace App\Nova\Filters;

use App\Models\DeviceLog;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class AreaFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $ids = $query->where('area', $value)->latest()->get(['id', 'device_id'])->unique('device_id')->pluck('id');
        return $query->whereIn('id', $ids);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return DeviceLog::whereNotNull('area')->get('area')->pluck('area')->unique()->map(function ($v) {
            return [
                'id' => $v,
                'name' => $v,
                'value' => $v,
            ];
        });
    }
}
