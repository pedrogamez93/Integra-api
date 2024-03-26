<?php

namespace App\Nova;

use Benjaminhirsch\NovaSlugField\Slug;
use Benjaminhirsch\NovaSlugField\TextWithSlug;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;

class Link extends Resource
{

    public static $group = 'Publicaciones';

    public static function label()
    {
        return 'Enlaces';
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Link';

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
        'name',
        'icon',
        'url',
        'target',
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
            TextWithSlug::make('Nombre', 'name')->rules('required')->slug('slug')
                ->creationRules('unique:links,name')
                ->updateRules('unique:links,name,{{resourceId}}'),
            Slug::make('slug')->rules('required')->creationRules('unique:links,slug'),
            Text::make('Icono', 'icon'),
            Text::make('Url', 'url'),
            Select::make('Target', 'target')->options([
                '_blank' => '_blank',
                '_self' => '_self',
            ]),
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
