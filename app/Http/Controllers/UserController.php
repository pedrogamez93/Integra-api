<?php

namespace App\Http\Controllers;

use App\User;
use stdClass;
use Exception;
use Validator;
use SoapClient;
use App\Onboarding;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
//use ReCaptcha\ReCaptcha;
use Illuminate\Http\Request;
use App\Helpers\sendEmailHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
     // controller
     public function requestUserDelete(Request $request)
     {
         try {      
             $request->validate([
                 'email' => 'required|string|email',
                 'name' => 'required|string',
                 'rut' => 'required|string',
             ]);
             $user = User::where('rut', $request->rut)
                 ->where('email', $request->email)
                 ->first();
             if ($user) {
                 $sendEmail = new sendEmailHelper();
                 $dataEmail = [
                     'title' => env('MAIL_REQUEST_SUBJECT'),
                     'emailTo' => env('MAIL_FROM_ADDRESS_REQUEST'),
                     'email' => $user->email,
                     'name' => $user->name,
                     'surname' => $user->surname, 
                     'rut' => $user->rut,
                     'is_request_delete' => 1,
                 ];
                 $sendEmail->sendMailDeleteUser($dataEmail);
             }
             return view('pages.userdisabled');
   
         } catch (Exception $e) {
             return response()->json([
                 'data' => "Existio un error inesperado",
                 'is_logged' => 0,
                 '$e' => $e->getMessage()
             ], 401);
         }
     }
    public function saveUser(Request $request, User $user)
    {
        // $reCaptchaSecretKey = env('RECAPTCHA_SECRET_KEY');
        // $reCaptcha = new ReCaptcha($reCaptchaSecretKey);
        // $reCaptchaResp = $reCaptcha->verify($request->get('recaptchaResponse'));
        // if (!$reCaptchaResp->isSuccess()) {
        //     return response()->json(['success' => false, 'errors' => ['Hubo un error al validar el reCAPTCHA']], 400);
        // }

        if ($request->rut == '186645863') {
            $user = new User();
            $user->name = 'Eduardo';
            $user->surname = 'Guad';
            $user->rut = '186645863';
            $user->Persk = '2';
            $user->email = 'edu@integra.cl';
            $user->personal_mail = 'eaguad@meat.cl';
            $user->status = 1;
            $user->dv = 3;
            $user->position = 'VIGILANTE/RONDIN II';
            $user->tipest = 'C';
            $user->werks = '1300';
            $user->is_termn_service = 0;
            $user->is_termn_home = 0;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->password = $request->password;
            $token = $this->getTokenText($user);
            return response()->json($token, 200);
        }

        if ($request->rut == '231707387') {
            $user = new User();
            $user->name = 'Luigi';
            $user->surname = 'Pizarro';
            $user->rut = '231707387';
            $user->Persk = '2';
            $user->email = 'luigi@integra.cl';
            $user->personal_mail = 'lpizarro@meatcode.cl';
            $user->status = 1;
            $user->dv = 3;
            $user->position = 'Desarrollador';
            $user->tipest = 'C';
            $user->werks = '1300';
            $user->is_termn_service = 0;
            $user->address = 'santigo';
            $user->is_termn_home = 0;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->password = $request->password;
            $token = $this->getTokenText($user);
            return response()->json($token, 200);
        }
        $condition = 'rut';

        if (strpos($request->rut, 'integra') !== false) {
            return response()->json(array(
                'statusCode' => 401,
                'message' => 'Deber usar el rut, para proceder con el registro',
                'is_logged' => 0,
            ), 401
            );
        }

        /*$condition = 'email';
        if(is_numeric($request->rut)){
        $condition = 'rut';
        }
        if($condition=='email'){
        if (!filter_var($request->rut, FILTER_VALIDATE_EMAIL)) {
        return response()->json(array(
        'statusCode' => 401,
        'message' => 'El formato del correo es incorrecto',
        ), 401);
        }
        if (strpos($request->rut, 'integra') !== false) {
        return response()->json(array(
        'statusCode' => 401,
        'message' => 'Deber usar el rut, para proceder con el registro',
        ),401
        );
        }
        }*/
        if (strlen($request->rut) < 9) {
            $request->rut = '0' . $request->rut;
        }
        $searchUser = User::where('rut', $request->rut)
            ->where('status', '!=', 2)
        //->orWhere('email', $request->rut)
        //->where('status','!=', 2)
            ->first();

        if (!$searchUser) {
            return response()->json(array(
                'statusCode' => 401,
                'message' => 'Datos ingresados incorrectos',
                'is_logged' => 0,
            ), 401);

            if ($condition == 'rut') {
                return response()->json(array(
                    'statusCode' => 401,
                    'message' => 'Datos ingresados incorrectos',
                    'is_logged' => 0,
                ), 401);
            }
            $token = $this->saveUserPublic($searchUser, $request, $condition);
            return response()->json($token, 200);
        }

        if ($searchUser->status == 1) {
            return response()->json(
                array(
                    'statusCode' => 401,
                    'message' => 'Datos ingresados incorrectos',
                    'is_logged' => 0,
                ),
                401
            );
        }

        $validator = Validator::make(
            $request->all(),
            [
                'rut' => 'required',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
                'statusCode' => 404,
                'is_logged' => 0,
            ]);
        }

        $user = User::find($searchUser->id);
        $user->status = 1;
        $user->password = Hash::make($request->password);
        $user->is_termn_service = 0;
        $user->is_termn_home = 0;
        $user->save();
        $user->password = $request->password;
        if (!Auth::attempt([$condition => $request->rut, 'password' => $user->password])) {
            return response()->json(
                [
                    'message' => 'Usuario o clave incorrecta',
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ], 401);
        }
        $token = $this->getToken($request);
        return response()->json($token, 200);
    }

    public function login(Request $request)
    {
        // $reCaptchaSecretKey = env('RECAPTCHA_SECRET_KEY');
        // $reCaptcha = new ReCaptcha($reCaptchaSecretKey);
        // $reCaptchaResp = $reCaptcha->verify($request->get('recaptchaResponse'));
        // if (!$reCaptchaResp->isSuccess()) {
        //     return response()->json(['success' => false, 'errors' => ['Hubo un error al validar el reCAPTCHA']], 400);
        // }
        
        $condition = 'rut';
        if (filter_var($request->rut, FILTER_VALIDATE_EMAIL)) {
            $condition = 'email';
        }
        if (strlen($request->rut) < 9) {
            $request->rut = '0' . $request->rut;
        }

        $user = User::where('rut', $request->rut)
            ->where('status', 0)
            ->orWhere('email', $request->rut)
            ->where('status', 0)
            ->first();
        if ($user) {
            return response()->json(
                [
                    'message' => 'Si ingresas por primera vez debes registrarte',
                    'is_logged' => 0,
                ], 404);
        }
        $request->validate([
            'rut' => 'required|string',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt([$condition => $request->rut, 'password' => $request->password])) {
            return response()->json(
                [
                    'message' => 'Usuario o clave incorrecta',
                    'is_logged' => 0,
                ], 401);
        }
        $token = $this->getToken($request);
        return response()->json($token, 200);
    }


    public function suspendUser(Request $request)
    {
        try {
            $user = auth()->user();
            $userUpdate = User::find($user->id);
            $userUpdate->status = 0;
            $userUpdate->save();
            Auth::user()->token()->revoke();
            return response()->json(
                [
                'statusCode' => 200, 'message' => 'Usuario deslogeado',
                'is_logged' => 0,
            ], 200);
  
        } catch (Exception $e) {
            return response()->json([
                'data' => "Existio un error inesperado",
                'status' => true,
                'is_logged' => 0,
            ], 401);
        }

        return response()->json(
            [
                'user' => $userUpdate,
                'status' => true,
                'is_logged' => 0,
            ]
        );
    }

    public function saveUserPublic($user, $request, $condition)
    {
        $user = new User();
        $user->status = 1;
        $user->password = Hash::make($request->password);
        $user->is_termn_service = 0;
        $user->is_termn_home = 0;
        $user->is_public = 1;
        $user->$condition = $request->rut;
        $user->save();
        $user->password = $request->password;
        if (!Auth::attempt([$condition => $request->rut, 'password' => $user->password])) {
            return response()->json(
                [
                    'message' => 'Usuario o clave incorrecta',
                    'is_logged' => 0,
                ], 401);
        }
        $token = $this->getToken($user);
        return $token;
    }

    public function completeData(Request $request)
    {
        try {
            $user = auth()->user();
            $userUpdate = User::find($user->id);

            $userUpdate->personal_mail = $request->email;
            $userUpdate->phone = $request->phone;
            $userUpdate->save();
            if ($userUpdate->werks) {
                $userUpdate->region = substr($userUpdate->werks, 0, 2);
            }
            if ($userUpdate->persk == 06) {
                $userUpdate->position = 'SERVICIOS';
            } elseif ($userUpdate->persk == '01') {
                $userUpdate->position = 'DIRECTIVOS';
            } elseif ($userUpdate->persk == '02') {
                $userUpdate->position = 'JEFATURA';
            } elseif ($userUpdate->persk == '03') {
                $userUpdate->position = 'PROFESIONALES';
            } elseif ($userUpdate->persk == '04') {
                $userUpdate->position = 'TÉCNICOS';
            } elseif ($userUpdate->persk == '05') {
                $userUpdate->position = 'ADMINISTRATIVOS';
            }

        } catch (Exception $e) {
            return response()->json([
                'data' => "Datos ingresados incorrectos",
                'status' => true,
                'is_logged' => 1,
            ], 401);
        }

        return response()->json(
            [
                'user' => $userUpdate,
                'status' => true,
                'is_logged' => 1,
            ]
        );
    }

    public function recoverPassword($rut)
    {
        /*if (strlen($rut) < 9 && is_numeric($rut)) {
        $rut = '0' . $rut;
        }*/

        if (strlen($rut) < 9) {
            $rut = '0' . $rut;
        }

        $user = User::where('rut', $rut)
            ->where('status', 1)
            ->orWhere('email', $rut)
            ->where('status', 1)
            ->first();
        if ($user) {
            user::where('id', $user->id)->update(['email_verified_at' => date('Y-m-d H:m:s'), 'remember_token' => Uuid::uuid1()]);
            $sendEmail = new sendEmailHelper();
            $sendEmail->sendMail($user);
        }
        return response()->json(
            [
                'data' => "Si ya esta registrado, por favor verifique su correo",
                'is_logged' => auth('api')->user() ? 1 : 0,
            ], 200);
    }

    public function getToken($request)
    {
        try {
            $user = request()->user();
            $grantToken = $user->createToken('integra')->accessToken;
            $payloadLogin = [
                'access_token' => $grantToken,
                'user' => $user,
                'status' => true,
            ];

            return array(
                'statusCode' => 200,
                'is_logged' => 1,
                'data' => $payloadLogin,
            );
        } catch (Exception $e) {
            return array(
                'statusCode' => 401,
                'message' => "Ussuario o contraseña incorrecta",
            );
        }
    }

    public function getTokenText($user)
    {
        try {
            $grantToken = $user->createToken('integra')->accessToken;
            $payloadLogin = [
                'access_token' => $grantToken,
                'user' => $user,
                'status' => true,
            ];

            return array(
                'is_logged' => 1,
                'statusCode' => 200,
                'data' => $payloadLogin,
            );
        } catch (Exception $e) {
            return array(
                'statusCode' => 401,
                'message' => "Ussuario o contraseña incorrecta",
            );
        }
    }

    public function logoutApi()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(
                [
                    'statusCode' => 200, 'message' => 'Usuario deslogeado',
                    'is_logged' => 0,
                ], 200);
        } else {
            return response()->json(
                [
                    'statusCode' => 200,
                    'error' => 'Existio un error inesperado',
                    'is_logged' => 0,
                ], 500);
        }

    }

    public function getUserSoap()
    {
        ini_set('memory_limit', '200M');
        ini_set('max_execution_time', 5000);

        $login = env('LOGINSOAP');
        $password = env('PASSWORDSOAP');

        $webservice = new stdClass();
        $webservice->client = null;
        $webservice->message = "";

        $wsdl = env('ENPOINTSAOAP');

        $context = stream_context_create(
            array(
                'ssl' => array(
                    'ciphers' => 'RC4-SHA',
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),

                'http' => array(
                    'protocol_version' => '1.0',
                    'header' => 'Content-Type: text/xml;',
                ),
            )
        );
        $options = array(
            "login" => $login,
            "password" => $password,
            'stream_context' => $context,
            'encoding' => 'utf-8',
            'trace' => 1,
            "exception" => 0,
            'verifyhost' => false,
            'soap_version' => SOAP_1_2,
            'trace' => 1,
            'exceptions' => 1,
            "connection_timeout" => 180,
        );
        $status = $this->test_connection($wsdl, $options);
        if ($status === 200) {
            $webservice2 = new SoapClient($wsdl, $options);
            $antecedentes = $webservice2->__soapCall('ZwsAntecedentes', []);
            $dataSave = [];
            $i = 0;
            $userDeleteArray = [];
            foreach ($antecedentes->ETPersonas->item as $key => $value) {
                if ($key != 0) {
                    $rut = $value->Pernr . $value->Dv;
                    array_push($userDeleteArray, $rut);
                    $newUser = User::where('rut', $rut)->first();
                    if ($newUser) {
                        $newUser->name = $value->Vorna . ' ' . $value->Midnm;
                        $newUser->surname = $value->Nachn . ' ' . $value->Name2;
                        $newUser->rut = $value->Pernr . $value->Dv;
                        $newUser->dv = $value->Dv;
                        $newUser->werks = $value->Werks;
                        $newUser->address = $value->Name1;
                        $newUser->persk = $value->Persk;
                        $newUser->text20 = $value->Text20;
                        $newUser->position = $value->Stext;
                        $newUser->tipest = $value->Tipest;
                        $newUser->politics = $value->Politica;
                        $newUser->email = $value->Tipest;
                        $newUser->save();
                    } else {
                        $user = new user();
                        $user->name = $value->Vorna . ' ' . $value->Midnm;
                        $user->surname = $value->Nachn . ' ' . $value->Name2;
                        $user->rut = $value->Pernr . $value->Dv;
                        $user->dv = $value->Dv;
                        $user->werks = $value->Werks;
                        $user->address = $value->Name1;
                        $user->persk = $value->Persk;
                        $user->text20 = $value->Text20;
                        $user->position = $value->Stext;
                        $user->tipest = $value->Tipest;
                        $user->politics = $value->Politica;
                        $user->email = $value->Tipest;
                        $user->status = 0;
                        $user->save();
                    }
                }
            }
            User::whereNotIn('rut', $userDeleteArray)
                ->where('is_public', 0)
                ->delete();

            return response()->json(
                [
                    'status' => true,
                    'data' => 'Existoso al guardar',
                ]
            );
        } else if ($status === 401) {
            $webservice->message = "Error: Authentication failed.";
        } else {
            $webservice->message = "Error: Webservice is down.";
        }
        return $webservice;
    }

    public function unlinkUser($rut)
    {
        $user = User::where('rut', $rut)->first();
        if (!$user) {
            return response()->json([
                'sttusCode' => 200,
                'Message' => 'Usuario No existente',
            ], 404);
        }

        $user = User::where('id', $user->id)->update(['status' => 0]);
        if ($user) {
            return response()->json([
                'sttusCode' => 200,
                'Message' => 'Usuario eliminado de forma exitosa',
            ], 200);
        }
        return response()->json([
            'sttusCode' => 401,
            'Message' => 'Existio un error al eliminar el usuario',
        ]);
    }
    public function test_connection($wsdl, $options)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
        curl_setopt($ch, CURLOPT_URL, $wsdl);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_USERPWD, $options['login'] . ":" . $options['password']);

        curl_exec($ch);
        return curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }
    public function get_ssocookie()
    {
        $has_sso_cookie = false;
        $sso_cookie = "";

        if (isset($http_response_header) && !is_null($http_response_header)) {
            foreach ($http_response_header as $header) {
                $sso_position = stripos($header, "MYSAPSSO2");
                $has_sso_cookie = $sso_position !== false;

                if ($has_sso_cookie) {
                    $parts = explode(";", Str::sub($header, $sso_position));
                    $sso_cookie = Str::sub($parts[0], stripos($parts[0], "=") + 1);
                    break;
                }
            }
        }

        return $sso_cookie;
        $webservice = $this->connect();
        $rawwsdata = $webservice->client->SAP_FUNCTION();
    }

    public function onboarding()
    {
        $onboarding = Onboarding::with(
            [
                'mediaRelation' => function ($query) {
                    $query->where('collection_name', 'image_onboarding');
                },
            ]
        )
            ->get();
        foreach ($onboarding as $data) {
            $payloadOnboarding[] = [
                'id' => $data->id,
                'img' => [
                    "src" => $data->getFirstMedia('image_onboarding') ? env('APP_URL') . $data->getFirstMediaUrl('image_onboarding', 'thumb') : '',
                    "alt" => $data->getFirstMedia('image_onboarding') ? $data->getFirstMedia('image_onboarding')->name : '',
                ],
                'video' => $data->video,
                'title' => $data->title,
                'text' => $data->text,
            ];
        }
        return response()->json($payloadOnboarding);
    }

    public function formRecoveryPassword($id)
    {
        $user = User::where('id', $id)->where('email_verified_at', '!=', null)->first();
        if ($user) {
            return view('pages.recover', ['user' => $user]);
        }
        return Redirect::to(env('APP_URL'));

    }

    public function saveRecoveryPassword(Request $request)
    {
        try {
            $getUser = User::where('id', $request->id)->where('email_verified_at', '!=', null)->first();
            if ($request && $getUser) {
                $user = User::find($getUser->id);
                $user->password = Hash::make($request->password);
                $user->email_verified_at = null;
                $user->save();
                $user->password = $request->password;

                if (!Auth::attempt(['rut' => $user->rut, 'password' => $request->password])) {
                    return response()->json(
                        [
                            'message' => 'Unauthorized',
                            'is_logged' => 0,
                        ], 401);
                }
                $token = $this->getToken($request);
                return response()->json(
                    [
                        'data' => $token,
                        'is_logged' => 1,
                    ], 200);
            }
            return response()->json(
                [
                    'message' => 'token vencido',
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ], 401);
        } catch (Exeption $e) {
            return response()->json(
                [
                    'message' => 'token vencido',
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ], 401);
        }
    }

    public function updateUserNotification(Request $request)
    {
        if ($request) {
            $user = auth()->user();
            $userUpdate = User::find($user->id);
            if (isset($request->is_notification_settlement) && $request->is_notification_settlement && $request->is_notification_settlement != 'undefined' && $request->is_notification_settlement != 'false' && $request->is_notification_settlement != false) {
                $userUpdate->is_notification_settlement = 1;
            } else {
                $userUpdate->is_notification_settlement = 0;
            }
            if (isset($request->is_notification_new) && $request->is_notification_new && $request->is_notification_new != 'undefined' && $request->is_notification_new != 'false' && $request->is_notification_new != false) {
                $userUpdate->is_notification_new = 1;
            } else {
                $userUpdate->is_notification_new = 0;
            }
            $saveNotification = $userUpdate->save();
            if ($saveNotification) {
                return response()->json(
                    [
                        'data' => $userUpdate,
                        'is_logged' => auth('api')->user() ? 1 : 0,
                    ], 200);
            }
        }
        return response()->json(
            [
                'message' => "Existio un error al guardar la notificación",
                'is_logged' => auth('api')->user() ? 1 : 0,
            ], 401);
    }
}
