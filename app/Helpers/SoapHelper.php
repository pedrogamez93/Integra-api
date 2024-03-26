<?php

namespace App\Helpers;

use Exception;
use SoapClient;
use stdClass;

class SoapHelper
{
    public function getSoap($url, $nameFunction, $payload)
    {
        try {
            $login = env('LOGINSOAP');
            $password = env('PASSWORDSOAP');

            $webservice = new stdClass();
            $webservice->client = null;
            $webservice->message = "";

            $wsdl = $url;

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
                try {
                    $webservice = new SoapClient($wsdl, $options);
                    $webservice->message = $webservice->__getFunctions();
                    $webservice->message = $webservice->__soapCall($nameFunction, $payload);
                    return [
                        'code' => 200,
                        'data' => $webservice->message,
                    ];
                } catch (Exception $e) {
                    return [
                        'code' => 405,
                        'message' => $e->getMessage(),
                    ];
                }
            } else if ($status === 401) {
                $webservice->message = 'Error: Authentication failed.';
                return [
                    'code' => 405,
                    'message' => $webservice->message,
                ];
                $webservice->message = "";
            } else {
                $webservice->message = 'Error: Webservice is down.';
                return [
                    'code' => 405,
                    'message' => $webservice->message,
                ];
            }
            return $webservice->message;
        } catch (Exception $e) {
            return
                [
                'code' => 405,
                'message' => 'Existio un error inesperado, por favor intete  mas tarde',
            ];
        }
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

        $webservice = $this->connect();
        $rawwsdata = $webservice->client->SAP_FUNCTION();
    }

    public function getRutClear($rut)
    {
        $rut = substr($rut, 0, strlen($rut) - 1);
        return $rut;
    }

    public function getSoapRent($url, $payload)
    {
        try {
            $login = 'IntegraApp';
            $password = 'Int2gr1App#2021';

            $webservice = new stdClass();
            $webservice->client = null;
            $webservice->message = "";

            $wsdl = $url;

            $context = stream_context_create(
                array(
                    'ssl' => array(
                        'ciphers' => 'RC4-SHA',
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ),

                    'http' => array(
                        'protocol_version' => '1.0',
                        'header' => 'Content-Type: application/soap+xml;',
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
                'soap_version' => SOAP_1_1,
                'trace' => 1,
                'exceptions' => 1,
                "connection_timeout" => 180,
            );

            $status = $this->test_connection($wsdl, $options);
           
            if ($status === 200) {
                try {
                    $webservice = new SoapClient($wsdl, $options);
                   
                    $webservice->message = $webservice->__getFunctions();
                    $webservice->message = $webservice->__soapCall('con_cert_1887_IApp', $payload);
                    return [
                        'code' => 200,
                        'data' => $webservice->message,
                    ];
                } catch (Exception $e) {
                    return [
                        'code' => 405,
                        'message' => $e->getMessage(),
                    ];
                }
            } else {
                $webservice->message = 'Error: Webservice is down.';
                return [
                    'code' => 405,
                    'message' => $webservice->message,
                ];
            }
            return $webservice->message;
        } 
        catch (Exception $e) {
            return
                [
                'code' => 405,
                'message' => 'Existio un error inesperado, por favor intete  mas tarde',
            ];
        }
   }
}
