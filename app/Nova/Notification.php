<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Notification extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Notification';

    public static function label()
    {
        return 'Lista de notificaciones';
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
            BelongsTo::make('Usuario', 'User', 'App\Nova\User')->rules('required'),
            Text::make('Tipo', 'type', function () {
                if ($this->type == 1) {
                    return "Liquidación de sueldo";
                }
                if ($this->type == 2) {
                    return "Noticia";
                }
                if ($this->type == 3) {
                    return "Comunicado";
                }
                if ($this->type == 4) {
                    return "Oferta laboral";
                }
            }),
            Boolean::make('¿Fue enviada?', 'is_send_notification')
                ->trueValue(1)
                ->falseValue(0),
            Boolean::make('¿Fue leida?', 'is_read')
                ->trueValue(1)
                ->falseValue(0),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('is_send_notification', 1);
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
