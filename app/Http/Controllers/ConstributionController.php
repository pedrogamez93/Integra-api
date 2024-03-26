<?php

namespace App\Http\Controllers;

use App\Constribution;
use App\Helpers\sendEmailHelper;
use App\User;
use App\UserContribution;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstributionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $date = Carbon::now();
        $constribution = Constribution::where('init_date', '<=', $date->toDateTimeString())
            ->where('end_date', '>=', $date->toDateTimeString())
            ->first();
        if (!$constribution && !isset($constribution->amounts)) {
            return response()->json(['data' => 'Existio un error, por favor intente mas tarde'], 404);
        }
        $constribution->rut = $user->rut;
        $constribution->full_name = "$user->name $user->surname";
        $constribution->image = env('APP_URL').'/storage/'. $constribution->gratitude_image;
        $amounts = [];
        foreach (json_decode($constribution->amounts) as $value) {
            $amounts[] = [
                'color' => $value->attributes->color,
                'value' => $value->attributes->value,
            ];
        }
        return response()->json(
            [
                'is_logged' => 1,
                'status' => true,
                'data' => [
                    'generalInformation' => $constribution, 'amounts' => $amounts,
                ],
            ]);
    }

    public function voluntaryContribution(Request $request)
    {
        try {
            $user = auth()->user();
            DB::beginTransaction();
            $date = Carbon::now();
            $constribution = Constribution::where('init_date', '<=', $date->toDateTimeString())
                ->where('end_date', '>=', $date->toDateTimeString())
                ->first();
            if (!$constribution) {
                return response()->json(['data' => 'Existio un error, por favor intente mas tarde'], 404);
            }
            $saveConstribution = $this->saveConstribution($user, $request, $constribution);
            if (!$saveConstribution) {
                return response()->json(['data' => 'Error al gurdar datos'], 500);
            }
            if (!$this->sendMail($user, $constribution, $date, $saveConstribution)) {
                return response()->json(['data' => 'Error al enviar mail'], 500);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => $e->getMessage()], 500);
        }
        return response()->json(
            [
                'is_logged' => 1,
                'status' => true,
                'data' => $saveConstribution,
            ]
        );
    }

    public function sendMail($user, $constribution, $date, $saveConstribution)
    {
        try {
            $sendEmail = new sendEmailHelper();
            $firstName = explode(' ', $user->name);
            $lastName = explode(' ', $user->surname);
            $dataEmail = [
                'email_constribution' => $constribution->email,
                'email_particular' => $user->personal_mail,
                'email_institutional' => $user->email,
                'name' => $user->name,
                'rut' => $user->rut,
                'first_name' => $firstName[0],
                'last_name' => $lastName[0],
                'personal_mail' => $user->personal_mail,
                'surname' => $user->surname,
                'is_constribution_email' => 1,
                'action' => 'Aporte',
                'title' => $constribution->title,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'amount' => $saveConstribution->amount,
            ];
            $sendEmail->sendMailConstributional($dataEmail);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function saveConstribution($user, $request, $constribution)
    {
        try {
            $userContribution = new UserContribution();
            $userContribution->constribution_id = $constribution->id;
            $userContribution->user_id = $user->id;
            $userContribution->amount = $request->amount;
            $userContribution->rut = $user->rut;
            $userContribution->save();

            $user = User::find($user->id);
            $user->is_contribution = 1;
            $user->save();
            $userContribution->gratitude = $constribution->gratitude;

        } catch (\Exeption $e) {
            return false;
        }
        return $userContribution;
    }
}
