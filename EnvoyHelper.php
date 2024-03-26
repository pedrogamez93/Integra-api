<?php
class EnvoyHelper
{
    public function loadEnv()
    {
        $dotenv = Dotenv\Dotenv::create(__DIR__);
        try {
            $dotenv->load();
        } catch (Exception $exception) {
            echo "No existe archivo .env";
            exit(1);
        }
    }
    public function start()
    {
        global $argv;
        if (!isset($argv[1])) {
            echo "The first parameter is mandatory";
            exit(1);
        }
        if (!isset($argv[2])) {
            echo "The second parameter is mandatory";
            exit(1);
        }
        switch ($argv[1]) {
            case 'get_env':
                $env = getenv(strtoupper($argv[2]));

                if ($env === false) {
                    echo "La variable $argv[2] no existe";
                    exit(1);
                }
                exit($env);
        }
    }
}
