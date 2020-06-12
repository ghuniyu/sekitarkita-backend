<?php

namespace App\Nova;

use App\Enums\ChangeRequestStatus;
use App\Enums\SIKMCategory;
use App\Nova\Actions\ApproveSIKM;
use App\Nova\Actions\PendingSIKM;
use App\Nova\Actions\RejectSIKM;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class SIKM extends Resource
{

    public static function singularLabel()
    {
        return 'SIKM';
    }

    public static function label()
    {
        return 'SIKM';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\SIKM';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            Text::make('Kode SIKM', 'id')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->readonly(),
            BelongsTo::make('Perangkat', 'device', Device::class),
            Text::make('NIK')
                ->hideFromIndex()
                ->rules('digits:16')
                ->sortable(),
            Text::make('Nama', 'name')
                ->sortable(),
            Text::make('Telepon', 'phone')
                ->hideFromIndex()
                ->sortable(),
            MorphTo::make(__('Asal'), 'originable')
                ->types([Kecamatan::class, Kabupaten::class, Provinsi::class])
                ->searchable(),
            MorphTo::make(__('Tujuan'), 'destinationable')
                ->types([Kecamatan::class, Kabupaten::class, Provinsi::class])
                ->searchable(),
            Select::make('Kategori SIKM', 'category')
                ->displayUsingLabels()
                ->options(SIKMCategory::toSelectArray())
                ->required(),
            Image::make('File KTP', 'ktp_file')
                ->required(),
            Image::make('Surat SWAB / Rapid', 'medical_file')
                ->required(),
            Date::make('Tanggal Terbit Surat', 'medical_issued')
                ->hideFromIndex()
                ->required(),
            Status::make('Status Pengajuan', 'status')
                ->displayUsing(function ($item){
                    return ChangeRequestStatus::getDescription($item);
                })
                ->loadingWhen([ChangeRequestStatus::getDescription(ChangeRequestStatus::PENDING)])
                ->failedWhen([ChangeRequestStatus::getDescription(ChangeRequestStatus::REJECT)])
                ->required(),
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
        return [
            ApproveSIKM::make(),
            RejectSIKM::make(),
            PendingSIKM::make()
        ];
    }
}
