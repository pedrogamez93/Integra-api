<?php

namespace App\Http\Controllers;

use App\GeneralOption;
use App\Helpers\sendEmailHelper;
use App\Helpers\SoapHelper;
use App\Helpers\UtilHelper;
use App\Termn;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->soapHelper = new SoapHelper();
    }

    public function updateUserTermCondition()
    {
        date_default_timezone_set('America/Santiago');
        $user = auth()->user();
        $getUser = User::find($user->id);
        $getUser->is_termn_service = 1;
        $getUser->updated_at_termn_service_liquidacion = date("Y-m-d H:i:s");

        $getUser->save();
        return response()->json(
            [
                'data' => $getUser,
                'is_logged' => auth('api')->user() ? 1 : 0,
            ], 200);
    }

    public function isCheckTermn()
    {
        $user = auth()->user();
        if ($user->is_termn_service) {
            return response()->json(
                [
                    'is_termn' => 1,
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ], 200);
        }
        return response()->json(
            [
                'is_termn' => 0,
                'is_logged' => auth('api')->user() ? 1 : 0,
            ], 200);
    }
    public function isAcceptanceTerms(Request $request)
    {
        $user = auth()->user();
        $soapHelper = new SoapHelper();
        $payload = [
            'parameters' => [
                'IAccion' => $request->is_acceptance,
                'IPernr' => $soapHelper->getRutClear($user->rut),
                'IUname' => '',
            ],
        ];
        $data = $soapHelper->getSoap(env('ENPOINTSAOAP'), 'ZwsPoliticas', $payload);
        if ($data['code'] == 200) {
            return response()->json($data->ERetorno, $data['code']);
        }
        return response()->json($data, $data['code']);
    }
    public function isAcceptanceTermsText()
    {
        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => Termn::where('is_home', '!=', 1)->first(),
            'statusCode' => 200,
        ]);
    }

    public function currentSettlementApi()
    {
        return auth()->user();
    }

    public function settlements()
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        date_default_timezone_set('America/Santiago');
        $utilHelper = new UtilHelper();
        $dayLast = date('d', (mktime(0, 0, 0, date('m') + 1, 1, date('Y')) - 1));
        $dayCurrent = date('d');
        $dateCurrent = date("d-m-Y");

        $getGeneralOption = GeneralOption::first();
        $dateTimeCurrent = strtotime(date('Y-m-d H:i:s'));
        $trasnformDate = date("Y-m-d H:i:s", strtotime($getGeneralOption->datetime));
        $currentSettlementDate = strtotime($trasnformDate);

        $dt = Carbon::createMidnightDate($dateCurrent)->settings([
            'monthOverflow' => false,
        ]);

        if ($dateTimeCurrent >= $currentSettlementDate) {
            $currentDate = $utilHelper->getMountString($dt->copy()->subMonths(1)->format('d-m-Y'));
            $up = $utilHelper->getMountString($dt->copy()->subMonths(3)->format('d-m-Y'));
        } else {
            $currentDate = $utilHelper->getMountString($dt->copy()->subMonths(2)->format('d-m-Y'));
            $up = $utilHelper->getMountString($dt->copy()->subMonths(4)->format('d-m-Y'));
        }

        return response()->json(
            [
                'is_logged' => auth('api')->user() ? 1 : 0,
                'current_date' => $currentDate,
                'latest_settlements' => [
                    'from' => $up,
                    'up' => $currentDate,
                ],
            ], 200);
    }

    public function currentSettlement(Request $request)
    {
        //setlocale(LC_TIME, 'es_ES.UTF-8');
        date_default_timezone_set('America/Santiago');
        $user = auth()->user();
        $soapHelper = new SoapHelper();
        $isValidLiquidatio = 0;
        $pdf = [];
        $annio = date('Y');
        $mount = date('m');
        $dayCurrent = date('d');
        $utilHelper = new UtilHelper();
        $dateCurrent = date("d-m-Y");
        $annioMount = date("Y-m", strtotime($dateCurrent));

        $getGeneralOption = GeneralOption::first();
        $trasnformDate = date("Y-m-d H:i:s", strtotime($getGeneralOption->datetime));
        $dateTimeCurrent = strtotime(date('Y-m-d H:i:s'));
        $currentSettlementDate = strtotime($getGeneralOption->datetime);
        $sendEmail = new sendEmailHelper();
        $dt = Carbon::createMidnightDate($dateCurrent)->settings([
            'monthOverflow' => false,
        ]);
        if ($request->type == 3) {
            if ($dateTimeCurrent >= $currentSettlementDate) {
                $dateFormat1 = $utilHelper->getMountString($dt->copy()->subMonths(3)->format('Y-m-d'));
                $dateFormat2 = $utilHelper->getMountString($dt->copy()->subMonths(2)->format('Y-m-d'));
                $dateFormat3 = $utilHelper->getMountString($dt->copy()->subMonths(1)->format('Y-m-d'));
                $period1 = $dt->copy()->subMonths(3)->format('Ym');
                $period2 = $dt->copy()->subMonths(2)->format('Ym');
                $period3 = $dt->copy()->subMonths(1)->format('Ym');
            } else {
                $dateFormat1 = $utilHelper->getMountString($dt->copy()->subMonths(4)->format('d-m-Y'));
                $dateFormat2 = $utilHelper->getMountString($dt->copy()->subMonths(3)->format('d-m-Y'));
                $dateFormat3 = $utilHelper->getMountString($dt->copy()->subMonths(2)->format('d-m-Y'));
                $period1 = $dt->copy()->subMonths(4)->format('Ym');
                $period2 = $dt->copy()->subMonths(3)->format('Ym');
                $period3 = $dt->copy()->subMonths(2)->format('Ym');
            }

            return response()->json(array(
                'is_logged' => auth('api')->user() ? 1 : 0,
                'data' => array(
                    array(
                        'icon' => 'insert_drive_file',
                        'label' => 'Liquidación',
                        'title' => $dateFormat3,
                        'period' => $period3,
                        'url' => '',
                    ),
                    array(
                        'icon' => 'insert_drive_file',
                        'label' => 'Liquidación',
                        'title' => $dateFormat2,
                        'period' => $period2,
                        'url' => '',
                    ),
                    array(
                        'icon' => 'insert_drive_file',
                        'label' => 'Liquidación',
                        'title' => $dateFormat1,
                        'period' => $period1,
                        'url' => '',
                    ),
                ),
            ));
        }
        if ($request->period > date('Ym') || $request->period == date('Ym')) {
            return response()->json(array(
                'message' => "Liquidación no disponible",
                'is_logged' => auth('api')->user() ? 1 : 0,
            ), 404);
        }
        if ($request->period == date("Ym", strtotime(date('Ym') . "-1 month"))) {
            if ($currentSettlementDate > $dateTimeCurrent) {
                return response()->json(array(
                    'message' => "Liquidación no disponible",
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ), 404);
            }
        }
        if ($request->type == 1) {
            $request->period = $dt->copy()->subMonths(1)->format('Ym');
            $annioMount = $dt->copy()->subMonths(1)->format('Y-m');
            if ($currentSettlementDate > $dateTimeCurrent) {
                $annioMount = $dt->copy()->subMonths(2)->format('Y-m');
                $request->period = $dt->copy()->subMonths(2)->format('Ym');
            }
        }
        if ($request->period < date('Ym') && $request->type != 1) {
            $annioMount = $request->period;
            $annio = substr($request->period, 0, 4);
            $mount = substr($request->period, 4, 6);
            $annioMount = $annio . '-' . $mount;
        }
        
        $request->type = 2;

        $name = Uuid::uuid1() . date('Y-m-dH-m-s') . '.pdf';
        $encodedPdf = $this->payloadSalarySettlement($request->period, $user, $request->type);
        $pdfBinary = base64_decode($encodedPdf->Respuesta);
        $user->is_service_email = 1;
        $user->pdf = $encodedPdf->Respuesta;
        $emailRequest = '';
        if (isset($request->email)) {
            $emailRequest = $request->email;
            $user->personal_mail = '';
            $user->email = '';
        }

        \Storage::disk('pdf')->put($name, $pdfBinary);
        $firstName = explode(' ', $user->name);
        $lastName = explode(' ', $user->surname);

        $dataEmail = [
            'pdf' => $encodedPdf->Respuesta,
            'email' => $user->email,
            'email_request' => $emailRequest,
            'name' => $user->name,
            'rut' => $user->rut,
            'first_name' => $firstName[0],
            'last_name' => $lastName[0],
            'personal_mail' => $user->personal_mail,
            'nameDocument' => "liquidacion-$annioMount.pdf",
            'surname' => $user->surname,
            'is_service_email' => 1,
            'action' => 'Liquidación de sueldo',
        ];

        $sendEmail->sendMailService($dataEmail);
        return response()->json(array(
            'is_logged' => auth('api')->user() ? 1 : 0,
            'file' => $utilHelper->getUrlImage($name),
            'title' => $utilHelper->getMountString($annioMount),
        ));
    }
    public function fileOpenPdf($file)
    {
        header("Content-Type: application/pdf");
        header("X-Frame-Options: 'ALLOW FROM: *'");
        readfile("$file");
    }

    public function payloadSalarySettlement($period, $user, $type)
    {
        $payload = [
            'parameters' => [
                'IPernr' => $this->soapHelper->getRutClear($user->rut),
                'ITipo' => $type,
                'IPeriodo' => $period ? $period : '',
            ],
        ];
        $data = $this->soapHelper->getSoap(env('ENPOINTSAOAP'), 'ZwsLiqSueldo', $payload);
        if ($data['code'] != 200) {
            return response()->json(
                [
                    'data' => $data['message'],
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ], $data['code']);
        }
        $encodedPdf = $data['data']->ESalidas->item;
        return $encodedPdf;
    }
}
