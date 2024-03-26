<?php

namespace App\Imports;

use App\User;
use App\UserRutList;
use Maatwebsite\Excel\Concerns\ToModel;

class UserRutListsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rut = $row[0];
        $str = str_replace("-", '', $rut);
        $str = str_replace("\n", '', $str);
        if (strlen($str) <= 8) {
            $str = '0' . $str;
        }
        $getUser = User::where('rut', $str)->first();
        //dd($getUser);
        return new UserRutList([
            'user_id' => $getUser->id,
            'user_rut_list_id' => $_REQUEST['name'],
        ]);
    }
}