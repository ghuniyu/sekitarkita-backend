<?php

namespace App\Nova;

use App\Enums\HealthStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class SelfCheck extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\SelfCheck';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'result';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'result', 'name', 'phone'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Device', 'device', Device::class)
                ->sortable()
                ->searchable(),
            Text::make('Nama', 'name')
                ->sortable(),
            Number::make('Usia', 'age')
                ->sortable(),
            Text::make('Nomor Telepon', 'phone')
                ->sortable(),
            Text::make('Alamat', 'address')
                ->sortable(),
            Boolean::make('Demam dalam 14 hari?', 'has_fever'),
            Boolean::make('Flu?', 'has_flu'),
            Boolean::make('Batuk?', 'has_cough'),
            Boolean::make('Sesak Nafas?', 'has_breath_problem'),
            Boolean::make('Nyeri Tenggorokan?', 'has_sore_throat'),
            Boolean::make('Pernah di Negara Transimisi?', 'has_in_infected_country'),
            Boolean::make('Pernah di Kota Transimisi?', 'has_in_infected_city'),
            Boolean::make('Berinteraksi langsung dengan pasien positif / Faskes?', 'has_direct_contact'),
            Select::make('Hasil', 'result')
                ->displayUsingLabels()
                ->options(HealthStatus::toSelectArray()),
        ];
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
