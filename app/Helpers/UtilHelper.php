<?php

namespace App\Helpers;

use App\Region;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use App\Position;
use App\District;

class UtilHelper
{
    public function getTimestamp($dateTime)
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        return strftime("%d de %B de %Y a las %H:%M HRS", strtotime($dateTime));
    }

    public function getTimestampNotification($dateTime)
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        return strftime("%A %d de %B", strtotime($dateTime));
    }

    public function getMountString($date)
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        date_default_timezone_set('America/Santiago');
        return strftime("%B %Y", strtotime($date));
    }

    public function previewSingle($date)
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        date_default_timezone_set('America/Santiago');

        return $date;
    }

    public function getDateCurrent()
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        date_default_timezone_set('America/Santiago');
        return date('Y-m-d H:i:s');
    }

    public function getTimestampFormat($dateTime)
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        return strftime("%A %d de %B", strtotime($dateTime));
    }

    public function getHourMinute($time)
    {
        return date('H:i', strtotime($time));
    }

    public function transformUtfToLocalDate($dateTime)
    {
        $transformDate = new DateTime($dateTime, new DateTimeZone('UTC'));
        $transformDate->setTimezone(new DateTimeZone('America/Santiago'));
        $dateTransform = $transformDate->format('Y-m-d');

        $date = self::getTimestamp($dateTransform);
        return $date;
    }

    public function transformDateNotification($dateTime)
    {
        $transformDate = new DateTime($dateTime, new DateTimeZone('UTC'));
        $transformDate->setTimezone(new DateTimeZone('America/Santiago'));
        $dateTransform = $transformDate->format('Y-m-d H:i:s');

        return $dateTransform;
    }

    public function pagination($page, $paginate, $payload)
    {
        $offSet = ($page * $paginate) - $paginate;
        $itemsForCurrentPage = array_slice($payload, $offSet, $paginate, true);
        $result = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($payload), $paginate, $page);
        $result = $result->toArray();
        return $result;
    }

    public function calculateDifferenceOfTwoHours($dbDate)
    {
        $serverDate = Carbon::now()->toDateTimeString();
        $convertDbDate = new DateTime($dbDate);
        $covertServerDate = new DateTime($serverDate);
        $interval = $convertDbDate->diff($covertServerDate);
        return $interval->format('%H-%i');
    }

    public function convertHourFormat()
    {
        $serverDate = Carbon::now()->toDateTimeString();
        $transformDate = new DateTime($serverDate, new DateTimeZone('UTC'));
        $transformDate->setTimezone(new DateTimeZone('America/Santiago'));
        return $transformDate->format('H:i');
    }

    public function getUrlImage($name)
    {
        return env('APP_URL') . "/pdf/$name";
    }

    public static function getUserByRol()
    {
        $where = '1 = 1 ';
        $user = auth()->user();
        $userResion = Region::where('id', $user->region_id)->first();
        if ($user->rol_id == 8 && $userResion) {
            $where = "regions.code =" . $userResion->code;
        }
        return $where;
    }

    public static function getUserTypeByRol()
    {
        $where = '1 = 1 ';
        $user = auth()->user();
        $userResion = Position::where('id', $user->text20)->first();
        if ($user->rol_id == 8 && $userResion) {
            $where = "positions.code =" . $userResion->code;
        }
        return $where;
    }

    public static function getUserDistricts()
    {
        $where = '1 = 1 ';
        $user = auth()->user();
        $userResion = District::where('code', $user->tipest)->first();
        if ($user->rol_id == 8 && $userResion) {
            $where = "districts.code =" . $userResion->code;
        }
        return $where;
    }

    public function validRol($user, $module = 'admin')
    {
        if ($user->rol_id == 1) {
            return true;
        }
        if ($module == 'post' && ($user->rol_id == 2 || $user->rol_id == 7 || $user->rol_id == 8)) {
            return true;
        }

        if ($module == 'release' && ($user->rol_id == 2 || $user->rol_id == 7 || $user->rol_id == 8)) {
            return true;
        }

        if ($module == 'helpdesk' && ($user->rol_id == 2 || $user->rol_id == 7 || $user->rol_id == 8)) {
            return true;
        }
        if ($module == 'faq' && ($user->rol_id == 2 || $user->rol_id == 7 || $user->rol_id == 8)) {
            return true;
        }
        if ($module == 'tutorial' && ($user->rol_id == 2 || $user->rol_id == 7 || $user->rol_id == 8)) {
            return true;
        }

        if ($module == 'onboarding' && ($user->rol_id == 2 || $user->rol_id == 7 || $user->rol_id == 8)) {
            return true;
        }

        if ($module == 'materialEducation' && ($user->rol_id == 2 || $user->rol_id == 5 || $user->rol_id == 7 || $user->rol_id == 8)) {
            return true;
        }

        if ($module == 'generalOption' && $user->rol_id == 3) {
            return true;
        }

        if ($module == 'userApp' && $user->rol_id == 4) {
            return true;
        }

        if ($module == 'constribution' && $user->rol_id == 6) {
            return true;
        }
        if ($module == 'survey' && $user->rol_id == 7) {
            return true;
        }

        return false;
    }

    public static function QueryRegion($user)
    {
        if ($user->region_id) {
            $region = Region::where('id', $user->region_id)->first();
            $where = " regions.code in($user->werks,$region->code)";
        } else {
            $where = " regions.code = $user->werks";
        }
        return $where;
    }

    public static function QueryPosition($user)
    {
        if ($user->text20) {
            $region = Position::where('id', $user->text20)->orWhere('name', $user->text20)->first();
            if ($region) {
                $where = " positions.code in($region->code)";
            } else {
                $where = " positions.name = '$user->text20'";
            }
        }
        return $where;
    }
}