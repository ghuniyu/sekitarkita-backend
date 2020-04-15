<?php

namespace App\Nova;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Profile extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\User';

    public static $displayInNavigation = false;


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public static $globallySearchable = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','name','email',
    ];

    public static function label()
    {
        return 'Profile';
    }


    public static function authorizable()
    {
        return false;
    }

    public function authorizedToView(Request $request)
    {
        return $this->onlyMine($request->user());
    }

    public function authorizedToUpdate(Request $request)
    {
        return $this->onlyMine($request->user());
    }

    public function onlyMine(User $user)
    {
        return $user['id'] === $this['id'];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $isSuperadmin = $request->user()['area'] == null;

        return [
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Area')
                ->help('ex. gorontalo')
                ->canSee(function () use($isSuperadmin) {
                    return $isSuperadmin;
                }),

            Text::make('Domain Access')
                ->help('ex. subdomain.domain.com')
                ->canSee(function () use($isSuperadmin) {
                    return $isSuperadmin;
                }),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
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
        return [];
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
