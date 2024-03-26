<?php

namespace App\Imports;

use App\SurveyUser;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class SurveyUsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $numRows = 0;

    public function model(array $row)
    {
        $rut = $row[0];
        $str = str_replace("-", '', $rut);
        $str = str_replace("\n", '', $str);
        if (strlen($str) <= 8) {
            $str = '0' . $str;
        }
        $getUser = User::where('rut', $str)->first();
        if ($getUser) {
            SurveyUser::where('user_rut_list_id', $_REQUEST['name'])
                ->where('user_id', $getUser->id)
                ->delete();
            ++$this->numRows;
            return new SurveyUser([
                'user_id' => $getUser->id,
                'user_rut_list_id' => $_REQUEST['name'],
            ]);
        }
    }

    public function getRowCount(): int
    {
        return $this->numRows;
    }
}