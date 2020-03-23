<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Saumini\Count\RelationshipCount;

class Device extends Resource
{
    public static $model = 'App\Models\Device';

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static $with = 'nearbies';

    public function fields(Request $request)
    {
        return [
            Text::make(__('Device MAC'), 'id')
                ->required()
                ->sortable(),
            Text::make('Label')
                ->help('optional')
                ->sortable(),
            Text::make(__('Telepon'), 'phone')
                ->help('optional')
                ->sortable(),
            Select::make(__('Health Condition'), 'health_condition')
                ->options(['healthy' => 'Sehat', 'pdp' => 'PDP', 'odp' => 'ODP'])
                ->displayUsingLabels()
                ->required()
                ->sortable(),
            RelationshipCount::make('Riwayat Interaksi', 'nearbies')
                ->sortable(),
            HasMany::make('Riwayat Interaksi', 'nearbies', Nearby::class)
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('nearbies as nearbies');
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
