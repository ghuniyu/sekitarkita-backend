<?php

namespace App\Nova;

use App\Nova\Filters\ChangeRequestFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
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
        'id','status','health_condition',
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
            Select::make('Status Kesehatan', 'health_condition')
                ->displayUsingLabels()
                ->options(['healthy' => 'Sehat', 'pdp' => 'PDP', 'odp' => 'ODP', 'confirmed' => 'Positif'])
                ->sortable()
                ->required(),
            Select::make('Status Pengajuan', 'status')
                ->displayUsingLabels()
                ->sortable()
                ->options(['pending' => 'Menunggu Verifikasi', 'approve' => 'Diterima', 'reject' => 'Ditolak'])
                ->required()
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
                ['Menunggu Verifikasi' => 'pending', 'Diterima' => 'approve', 'Ditolak' => 'reject'],
                'Status Pengajuan'
            )),
            (new ChangeRequestFilter(
                'health_condition',
                ['Sehat' => 'healthy', 'PDP' => 'pdp', 'ODP' => 'odp', 'Positif' => 'confirmed'],
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
