<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SoapClient;
use stdClass;

class synchUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
}
