<?php

namespace App\Nova;

use Fourstacks\NovaRepeatableFields\Repeater;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;

class Termn extends Resource
{

    public static $group = 'Terminos y condiciones';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Termn';

    public static function label()
    {
        return 'Término y condición';
    }

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
            Boolean::make('¿Es el iniccio de la aplicación?', 'is_home')
                ->trueValue(1)
                ->falseValue(0),
            Repeater::make('Nombre', 'name')
                ->addField([
                    'label' => 'Name',
                    'name' => 'name',
                    'type' => 'textarea',
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
