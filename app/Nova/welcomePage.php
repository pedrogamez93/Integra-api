<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Benjaminhirsch\NovaSlugField\Slug;
use Benjaminhirsch\NovaSlugField\TextWithSlug;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;

class welcomePage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static function label()
    {
        return 'Página de Bienvenida';
    }


    public static $model = 'App\welcomePage';

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
        'title',
        'icon',
        'slug',
        'class',
        'start_date',
        'end_date',
        'type_certificate'
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
            TextWithSlug::make('Titulo', 'title')->rules('required')
                ->creationRules('unique:welcome_pages,title')
                ->updateRules('unique:welcome_pages,title,{{resourceId}}'),
            Text::make('slug')->rules('required')->creationRules('unique:welcome_pages'),
            Text::make('icono', 'icon'),
            Text::make('Color', 'class'),
            DateTime::make('Fecha Inicio', 'start_date'),
            DateTime::make('Fecha Fin', 'end_date'),
            //Boolean::make('¿Es publico?','is_public'),
            Boolean::make('Certificado','type_certificate'),
            Boolean::make('Ver en Home','view_home'),
            Text::make('Titulo del Certificado', 'title_certificate'),
            Text::make('Description Corta', 'description_certificate'),
            Text::make('Nombre del Boton', 'label_button')
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

    public static function authorizedToCreate(Request $request)
    {
        return true;
    }
}
