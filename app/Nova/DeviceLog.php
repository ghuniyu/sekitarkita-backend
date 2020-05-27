<?php

namespace App\Nova;

use App\Nova\Filters\AreaFilter;
use GeneaLabs\NovaMapMarkerField\MapMarker;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaButton\Button;

class DeviceLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\DeviceLog';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'area', 'device_id'
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($area = $request->user()['area']) {
            return $query->where('area', 'like', "%$area%");
        }

        return $query;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make(__('Device'), 'device', Device::class)
                ->sortable(),
            Text::make(__('Device Sekitar'), 'nearby_device'),
            Text::make(__('Nama Device Sekitar'), 'device_name'),
            MapMarker::make('Lokasi')
                ->hideFromIndex()
                ->defaultLatitude('-6.914744')
                ->defaultLongitude('107.609810')
                ->rules(['required', 'numeric']),
            Number::make(__('Kecepatan'), 'speed'),
            Text::make(__('Area'), 'area')
                ->sortable(),
            DateTime::make('On Date', 'created_at')
                ->format("D-MM-Y hh:mm:ss")
                ->sortable(),
            Button::make('Lihat Aktifitas')->link(route('tracking.view',  [
                'device_id' => $this['device_id']
            ]))->style('primary')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            (new AreaFilter)
                ->canSee(function ($request) {
                    return $request->user()['area'] == null;
                })
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
