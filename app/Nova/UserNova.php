<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\Select;
use App\Helpers\UtilHelper;

class UserNova extends Resource
{
    public static $group = 'Usuario';

    public static function label()
    {
        return 'Usuario administrador';
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\UserNova';

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
        'surname',
        'email',
        'personal_mail',
        'rut',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $where = UtilHelper::getUserByRol();
        return [
            ID::make()->sortable(),

            Gravatar::make(),

            Text::make('Nombres', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Apellidos', 'surname')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Correo', 'email')
                ->sortable(),
            Password::make('Clave', 'Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

           // Text::make('Código de región', 'werks')->hideFromIndex(),

            Select::make('Región', 'region_id')
            ->options(\App\Region::whereRaw($where)->get()->mapWithKeys(function ($region) {
                return [$region->id => $region->name];
            }))->rules('required'),

            Boolean::make('¿Es admin?', 'is_admin')
                ->withMeta(['value' => $this->is_admin ?? true])
                ->trueValue(1)
                ->falseValue(0),
            BelongsTo::make('Rol', 'Rol', 'App\Nova\Rol')->nullable(),

            NovaDependencyContainer::make([
                BelongsTo::make('Region', 'region', 'App\Nova\Region')->nullable(),
            ])->dependsOn('Rol.id', 8),

            BelongsTo::make('Region', 'region', 'App\Nova\Region')->nullable()->hideFromIndex()->hideWhenCreating()->hideWhenUpdating(),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('is_admin', '=', 1);
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