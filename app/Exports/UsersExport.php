<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings
{
    use Exportable;
    public function __construct($startDate, $endDate, $type)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type;
    }

    public function query()
    {
        if($this->type == 1) {
            return User::query()
            ->select('users.id', 'users.name', 'users.surname', 'users.rut','users.position', 
            'users.address as dependencia','users.phone','users.email','users.personal_mail',
            'users.created_at',
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_termn_service = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de términos para la sección de servicios'
                FROM
                users as userT2
                WHERE
                users.id = users.id
                LIMIT
                1
            ) as is_termn_service
            "),
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_termn_home = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de términos para el home'
                FROM
                users as userT2
                WHERE
                users.id = userT2.id
                LIMIT
                1
            ) as is_termn_home
            "),
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_notification_settlement = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de notificación de sueldo'
                FROM
                users as userT2
                WHERE
                users.id = userT2.id
                LIMIT
                1
            ) as is_notification_settlement
            "),
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_notification_new = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de notificaciones de noticias'
                FROM
                users as userT2
                WHERE
                users.id = userT2.id
                LIMIT
                1
            ) as is_notification_new
            "),
            'users.updated_at_termn_service_home',
            'users.updated_at_termn_service_liquidacion')
            ->whereBetween('updated_at_termn_service_home', [$this->startDate, $this->endDate]);
        } else {
            return User::query()
            //->select('users.id', 'users.name', 'users.surname', 'users.rut', 'users.email', 'updated_at_termn_service_home', DB::raw('count(log_user.user_id) as frecuencia'))
            ->select('users.id', 'users.name', 'users.surname', 'users.rut','users.position', 
            'users.address as dependencia','users.phone','users.email','users.personal_mail',
            'users.created_at',
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_termn_service = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de términos para la sección de servicios'
                FROM
                users as userT2
                WHERE
                users.id = users.id
                LIMIT
                1
            ) as is_termn_service
            "),
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_termn_home = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de términos para el home'
                FROM
                users as userT2
                WHERE
                users.id = userT2.id
                LIMIT
                1
            ) as is_termn_home
            "),
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_notification_settlement = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de notificación de sueldo'
                FROM
                users as userT2
                WHERE
                users.id = userT2.id
                LIMIT
                1
            ) as is_notification_settlement
            "),
            DB::raw("(
                SELECT
                CASE
                WHEN users.is_notification_new = 0 THEN 'NO'
                ELSE 'SI'
                    END as 'aceptación de notificaciones de noticias'
                FROM
                users as userT2
                WHERE
                users.id = userT2.id
                LIMIT
                1
            ) as is_notification_new
            "),
            'users.updated_at_termn_service_home',
            'users.updated_at_termn_service_liquidacion',
            DB::raw('count(log_user.user_id) as frecuencia'))
            ->join('log_user', 'log_user.user_id', 'users.id')
            ->whereBetween('log_user.created_at', [$this->startDate, $this->endDate])
            ->groupBy('users.id');
        }
    }

    public function headings(): array
    {
        return ["id", "Nombre", "Apellido", "Rut", 'Position','Dependencia','Telefono', "Correo Institucional",
        "Correo Personal",
        "Fecha de Registro",
        'aceptación de términos para la sección de servicios',
        'aceptación de términos para el home',
        "aceptación de notificación de sueldo",
        "aceptación de notificaciones de noticias",
        "fecha de actualización de términos y condiciones del home",
        "fecha de actualización de términos y condiciones de los servicios",
        "Fecha ultimo ingreso",
        "Frecuencia"];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {   
    //     if($this->type == 1) {
    //         return User::whereBetween('updated_at_termn_service_home', [$this->startDate, $this->endDate])->get();
    //     } else {
    //         return User::select('users.*', DB::raw('count(log_user.user_id) as frecuencia'))
    //         ->join('log_user', 'log_user.user_id', 'users.id')
    //         ->whereBetween('log_user.created_at', [$this->startDate, $this->endDate])
    //         ->groupBy('users.id')
    //         ->get();
    //     }
    // }
}
