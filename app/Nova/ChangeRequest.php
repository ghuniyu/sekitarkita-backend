<?php

namespace App\Nova;

use App\Enums\ChangeRequestStatus;
use App\Enums\HealthStatus;
use App\Nova\Filters\ChangeRequestFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChangeRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\ChangeRequest';

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
        'id', 'status', 'user_status', 'nik', 'name', 'phone'
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($area = $request->user()['area']) {
            $query->whereHas('device', function ($q) use ($area) {
                return $q->where('last_known_area', 'like', "%$area%");
            });
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
            BelongsTo::make('Device'),
            Select::make('Merubah ke', 'user_status')
                ->displayUsingLabels()
                ->options(HealthStatus::toSelectArray())
                ->sortable()
                ->required(),
            Select::make('Status Pengajuan', 'status')
                ->displayUsingLabels()
                ->sortable()
                ->options(ChangeRequestStatus::toSelectArray())
                ->required(),
            Text::make("NIK", 'nik')
                ->sortable()
                ->required(),
            Text::make("Nama", 'name')
                ->displayUsing(function ($resource) {
                    return ucwords($resource);
                })
                ->sortable()
                ->required(),
            Text::make("Nomor Telepon", 'phone')
                ->sortable()
                ->required(),
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
            (new ChangeRequestFilter(
                'status',
                array_flip(ChangeRequestStatus::toSelectArray()),
                'Status Pengajuan'
            )),
            (new ChangeRequestFilter(
                'user_status',
                array_flip(HealthStatus::toSelectArray()),
                'Status Kesehatan'
            )),
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
