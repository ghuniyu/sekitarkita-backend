<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
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
        'id','phone'
    ];

    public static $with = 'nearbies';

    public function fields(Request $request)
    {
        return [
            Text::make(__('Device MAC'), 'id')
                ->required()
                ->sortable(),
            Text::make(__('Nama Perangkat'), 'device_name'),
            Text::make('Label')
                ->help('optional')
                ->sortable(),
            Text::make(__('Telepon'), 'phone')
                ->help('optional')
                ->sortable(),
            DateTime::make(__('Terakhir Online'), 'updated_at')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->format("D-MM-Y hh:mm:ss")
                ->sortable(),
            Select::make(__('Health Condition'), 'health_condition')
                ->options(['healthy' => 'Sehat', 'pdp' => 'PDP', 'odp' => 'ODP', 'confirmed' => 'Positif'])
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
}
