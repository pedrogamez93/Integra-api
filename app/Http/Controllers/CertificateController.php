<?php

namespace App\Http\Controllers;

use App\Helpers\sendEmailHelper;
use App\Helpers\SoapHelper;
use App\Helpers\UtilHelper;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use SoapClient;
use App\welcomePage;

class CertificateController extends Controller
{
    public function certificate(Request $request)
    {
        $user = auth()->user();
        $soapHelper = new SoapHelper();
        $payload = [
            'parameters' => [
                'IAccion' => $request->action,
                'IPernr' => $soapHelper->getRutClear($user->rut),
                'ITipo' => $request->type,
            ],
        ];
        $data = $soapHelper->getSoap(env('ENPOINTSAOAP'), 'ZwsCertificado', $payload);
        $title = '';
        if ($request->type == 1) {
            $title = 'Sueldo Base';
        } elseif ($request->type == 2) {
            $title = 'Antigüedad';
        } elseif ($request->type == 3) {
            $title = 'Cargo y Fecha de Ingreso';
        }
        if ($data['code'] == 200) {
            $encodedPdf = $data['data']->ERespuesta;
            $utilHelper = new UtilHelper();
            $name = Uuid::uuid1() . date('Y-m-dH-m-s') . '.pdf';
            $pdfBinary = base64_decode($encodedPdf);
            \Storage::disk('pdf')->put($name, $pdfBinary);
            $sendEmail = new sendEmailHelper();

            $emailRequest = '';
            if (isset($request->email)) {
                $emailRequest = $request->email;
                $user->personal_mail = '';
                $user->mail = '';
            }
            $firstName = explode(' ', $user->name);
            $lastName = explode(' ', $user->surname);

            $dataEmail = [
                'pdf' => $data['data']->ERespuesta,
                'email' => $user->email,
                'first_name' => $firstName[0],
                'last_name' => $lastName[0],
                'rut' => $user->rut,
                'personal_mail' => $user->personal_mail,
                'name' => $user->name,
                'email_request' => $emailRequest,
                'surname' => $user->surname,
                'is_certificate' => 1,
                'action' => 'Certificado',
                'nameDocument' => 'Certificado-' . date('Y-m-d') . '.pdf',
            ];
            $sendEmail->sendMailService($dataEmail);

            return response()->json(array(
                'file' => $utilHelper->getUrlImage($name),
                'title' => $title,
                'is_logged' => auth('api')->user() ? 1 : 0,
            ));
        }
        return response()->json($data, $data['code']);

    }

    public function test(Request $request)
    {
        //$user = auth()->user();
        $soapHelper = new SoapHelper();
        $payload = [
            'parameters' => [
                'IAccion' => 1,
                'IPernr' => '24548760',
                'ITipo' => 1,
            ],
        ];
        //dd("####");
        $data = $soapHelper->getSoap(env('ENPOINTSAOAP'), 'ZwsCertificado', $payload);
        if ($data['code'] == 200) {
            header('Content-Type: application/pdf');
            echo base64_decode($data['data']->ERespuesta);
        }
        return response()->json($data, $data['code']);

    }

    public function certificateRent(Request $request)
    {
        $user = auth()->user();
        $soapHelper = new SoapHelper();
        $payload = [
            'parameters' => [
                'rudetb' => $soapHelper->getRutClear($user->rut),//'14190792'
            ],
        ];

        $info = welcomePage::where('slug', 'certificados/renta-1887')->first();

        $data = $soapHelper->getSoapRent('http://webservicesiapp.integra.cl/ws_int_02/Con_INT?wsdl', $payload);
        if ($data['code'] == 200) {
            $encodedPdf = $data['data']->return;
            $utilHelper = new UtilHelper();
            $name = Uuid::uuid1() . date('Y-m-dH-m-s') . '.pdf';
            $pdfBinary = base64_decode($encodedPdf);
            \Storage::disk('pdf')->put($name, $pdfBinary);
        }

        $sendEmail = new sendEmailHelper();

        $emailRequest = '';
        if (isset($request->email)) {
            $emailRequest = $request->email;
            $user->personal_mail = '';
            $user->mail = '';
        }
        $firstName = explode(' ', $user->name);
        $lastName = explode(' ', $user->surname);

        $dataEmail = [
            'pdf' => $data['data']->return,
            'email' => $user->email,
            'first_name' => $firstName[0],
            'last_name' => $lastName[0],
            'rut' => $user->rut,
            'personal_mail' => $user->personal_mail,
            'name' => $user->name,
            'email_request' => $emailRequest,
            'surname' => $user->surname,
            'is_certificate_renta' => 1,
            'action' => 'Certificado',
            'nameDocument' => 'Certificado-' . date('Y-m-d') . '.pdf',
        ];
        $sendEmail->sendMailService($dataEmail);


        return response()->json(array(
            'file' => $utilHelper->getUrlImage($name),
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => $info
        ));
    }
}
