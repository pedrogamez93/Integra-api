<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Nova\Actions\UnlinkUser;
use App\Nova\Actions\UsersReport;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\Select;
use App\Helpers\UtilHelper;
use ZiffDavis\Nova\MultiSelect\MultiSelect;

class User extends Resource
{
    public static $group = 'Usuario';

    public static function label()
    {
        return 'Usuarios de la app';
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\User';

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
        'personal_mail',
        'email',
        'rut',
        'werks',
        'address',
        'persk',
        'text20',
        'position',
        'tipest',
        'phone',
        'politics',
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
        $whereTypeCarge = UtilHelper::getUserTypeByRol();
        $whereDistricts = UtilHelper::getUserDistricts();
        return [
            ID::make()->sortable(),

            Gravatar::make(),

            Text::make('Nombres', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Apellidos', 'surname')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Correo Institucional', 'email'),
            Text::make('Correo Personal', 'personal_mail'),

            Text::make('Rut', 'rut')->rules('required'),
           // Text::make('Código de región', 'werks')->hideFromIndex(),
            Select::make('Región', 'region_id')
            ->options(\App\Region::whereRaw($where)->get()->mapWithKeys(function ($region) {
                return [$region->id => $region->name];
            }))->rules('required'),

            Text::make('Persk')->hideFromIndex(),
           // Text::make('Tipo', 'text20')->hideFromIndex(),
            
            Select::make('Cargo', 'text20')
            ->options(\App\Position::whereRaw($whereTypeCarge)->get()->mapWithKeys(function ($position) {
                return [$position->id => $position->name];
            }))->rules('required'),

            Text::make('Tipo', 'position')->hideFromIndex(),

            Select::make('Dependencia', 'tipest')
            ->options(\App\District::whereRaw($whereDistricts)->get()->mapWithKeys(function ($districts) {
                return [$districts->code => $districts->name];
            }))->rules('required'),


            Text::make('Teléfono', 'phone')->hideFromIndex(),
            Text::make('Políticas', 'politics')->hideFromIndex(),
            Text::make('Dirección', 'address')->hideFromIndex(),
            Boolean::make('¿Estatus?', 'status')->hideFromIndex()
                ->trueValue(1)
                ->falseValue(0),

            Password::make('Clave', 'Password')->hideFromIndex()
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            Boolean::make('¿Es admin?', 'is_admin')
                ->trueValue(1)
                ->falseValue(0)
                ->hideFromIndex(),

            Boolean::make('¿Notificaciones para noticias?', 'is_notification_new')
                ->trueValue(1)
                ->falseValue(0)
                ->hideFromIndex(),

            Boolean::make('¿Notificaciones para liquidación?', 'is_notification_settlement')
                ->trueValue(1)
                ->falseValue(0)
                ->hideFromIndex(),

            Boolean::make('¿Terminos y condiciones servicios?', 'is_termn_service')
                ->trueValue(1)
                ->falseValue(0)
                ->hideFromIndex(),

            Boolean::make('¿Terminos y condiciones home?', 'is_termn_home')
                ->trueValue(1)
                ->falseValue(0)
                ->hideFromIndex(),

            Boolean::make('¿Es público?', 'is_public')
                ->trueValue(1)
                ->falseValue(0)
                ->hideFromIndex(),

            DateTime::make('Fecha de Términos del inicio de la app', 'updated_at_termn_service_home')->format('DD MMM YYYY hh mm ss'),
            DateTime::make('Fecha de Términos de la liquidación', 'updated_at_termn_service_liquidacion')->format('DD MMM YYYY hh mm ss'),

            BelongsTo::make('Rol', 'Rol', 'App\Nova\Rol')->nullable(),

            NovaDependencyContainer::make([
                BelongsTo::make('Region', 'region', 'App\Nova\Region')->nullable(),
            ])->dependsOn('Rol.id', 8),

            BelongsTo::make('Region', 'region', 'App\Nova\Region')->nullable()->hideFromIndex()->hideWhenCreating()->hideWhenUpdating(),
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('status', '!=', 2);
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
            (new UnlinkUser)->confirmText('Estas seguro que desea eliminar este usuario?')
                ->confirmButtonText('Si')
                ->cancelButtonText("No"),    
            //    new DownloadExcel,
            //select * from users where updated_at_termn_service_home BETWEEN "2021-12-01 00:00:00" AND "2022-03-31 23:59:59"
            //(new ReportUser()),   
            (new UsersReport)
           // DownloadExcel::download(new UserExport(2019), 'users.xlsx'),
        ];
    }
}