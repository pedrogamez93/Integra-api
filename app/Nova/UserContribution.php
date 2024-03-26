<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Titasgailius\SearchRelations\SearchesRelations;

class UserContribution extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    use SearchesRelations;

    public static $group = 'Aportes';

    public static $searchRelations = [
        'user' => ['name', 'surname'],
        'constribution' => ['title'],
    ];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static function label()
    {
        return 'Usuarios Aporte';
    }

    public static $model = 'App\UserContribution';

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
        'amount',
        'rut',
        'created_at',
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
            BelongsTo::make('Constribución', 'Constribution', 'App\Nova\Constribution')->rules('required'),
            BelongsTo::make('Usuario', 'User', 'App\Nova\User')->rules('required'),
            Text::make('Precio', 'amount'),
            Text::make('Rut', 'rut'),
            DateTime::make('Fecha', 'created_at')->format('DD-MM-YYYY HH:mm:ss')->rules('required'),
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
        return [
            (new DownloadExcel)->withHeadings(),
        ];
    }
}
