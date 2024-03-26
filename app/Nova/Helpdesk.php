<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;

class Helpdesk extends Resource
{
    public static $group = 'Ayuda';

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return 'Tarjeta de Contacto';
    }

    /**
     * @return string
     */
    public static function label()
    {
        return 'Tarjetas de Contacto';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Helpdesk';

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
        'id',
        'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Título', 'title'),

            Flexible::make('Contacto directo', 'content')
                ->addLayout('Sección', 'contact', [
                    Text::make('Icono', 'icon'),
                    Text::make('Titulo', 'title'),
                    Text::make('Datos', 'label'),
                    Text::make('Enlace', 'value'),
                ]),
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
