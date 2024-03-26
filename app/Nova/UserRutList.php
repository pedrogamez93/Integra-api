<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Benjaminhirsch\NovaSlugField\Slug;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZiffDavis\Nova\MultiSelect\MultiSelect;
use Benjaminhirsch\NovaSlugField\TextWithSlug;

class UserRutList extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static $group = 'Encuestas';

    public static $model = 'App\UserRutList';

    public static function label()
    {
        return 'Listado de rut';
    }

    public static function singularLabel()
    {
        return 'Listado de rut';
    }

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
        'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            TextWithSlug::make('Título', 'name')->slug('slug')
            ->creationRules('unique:user_rut_lists,name')
            ->sortable()
            ->updateRules('unique:user_rut_lists,name,{{resourceId}}'),
            Slug::make('slug')->creationRules('unique:user_rut_lists,slug'),
            MultiSelect::make('Usuario', 'user')->options(\App\UserNova::select('rut as name', 'id')->whereNotNull('rut')->get())->placeHolder('Seleccione los usuarios'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}