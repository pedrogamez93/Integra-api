<?php

namespace App\Nova\Actions;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use App\Exports\UsersExport;
use Carbon\Carbon;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\URL;

class UsersReport extends Action
{
    public $name = 'Reporte';

    use InteractsWithQueue, Queueable;
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function model(array $row)
    {
        return new User([
            'name'  => $row['name'],
            'email' => $row['email'],
        ]);
    }
    
    public function handle(ActionFields $fields, Collection $models)
    {
      $startDate = Carbon::createFromFormat('Y-m-d', $fields->startDate)->format('d_m_Y');
      $endDate = Carbon::createFromFormat('Y-m-d', $fields->endDate)->format('d_m_Y');
      $nameFile = $fields->type == 1 ? 'cantidad_usuarios_'.$startDate.'_'.$endDate.'.xlsx' : 'frecuencia_ingreso_'.$startDate.'_'.$endDate.'.xlsx';
      $response = Excel::download(new UsersExport($fields->startDate, $fields->endDate, $fields->type), $nameFile);

      return  Action::download($this->getDownloadUrl($response, $nameFile),$nameFile);
    }
        /**
     * @param BinaryFileResponse $response
     *
     * @return string
     */
    protected function getDownloadUrl(BinaryFileResponse $response, $nameFile): string
    {
        return URL::temporarySignedRoute('laravel-nova-excel.download', now()->addMinutes(1), [
            'path'     => encrypt($response->getFile()->getPathname()),
            'filename' => $nameFile,
        ]);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Date::make(__('Fecha Inicio'), 'startDate'),
            Date::make(__('Fecha Fin'), 'endDate'),
            Select::make(__('Tipo Reporte'), 'type')->options([
                '1' => 'Cantidad de usuarios únicos que ingresan',
                '2' => 'Frecuencia de ingreso de usuarios únicos',
            ])
        ];
    }
}
