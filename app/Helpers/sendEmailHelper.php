<?php

namespace App\Helpers;

use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;

class sendEmailHelper
{
    public function sendMultipleEmail($data)
    {
        foreach ($data as $value) {
            Mail::to($value['email'])->queue(new EmailVerification($value));
        }
    }

    public function sendMailService($data)
    {
        if ($data['email']) {
            Mail::to($data['email'])->queue(new EmailVerification($data));
        }
        if ($data['personal_mail']) {
            Mail::to($data['personal_mail'])->queue(new EmailVerification($data));
        }
        if ($data['email_request']) {
            Mail::to($data['email_request'])->queue(new EmailVerification($data));
        }
    }

    public function sendMailConstributional($data)
    {
        if ($data['email_constribution']) {
            Mail::to($data['email_constribution'])->queue(new EmailVerification($data));
        }
        if ($data['email_particular']) {
            Mail::to($data['email_particular'])->queue(new EmailVerification($data));
        }
        if ($data['email_institutional']) {
            Mail::to($data['email_institutional'])->queue(new EmailVerification($data));
        }
    }

    public function sendMail($data)
    {
        if (isset($data->email)) {
            Mail::to($data->email)->queue(new EmailVerification($data));
        }
        if (isset($data->personal_mail)) {
            Mail::to($data->personal_mail)->queue(new EmailVerification($data));
        }
    }
    public function sendMailDeleteUser($data)
    {
        if (isset($data['emailTo'])) {
            Mail::to($data['emailTo'])->queue(new EmailVerification($data));
        }
    }
}
