<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;
    protected $value;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        if (isset($this->value['is_certificate'])) {
            return $this->view('email.email_certificate')->subject(env('DOCUMENT_SUBJECT_CERTIFICATE'))->replyTo(env('MAIL_TO'))->with([
                'data' => $this->value,
            ])->attachData(base64_decode($this->value['pdf']), $this->value['nameDocument'], [
                'mime' => 'application/pdf',
            ]);
        }
        if (isset($this->value['is_certificate_renta'])) {
            return $this->view('email.email_certificate_renta')->subject(env('DOCUMENT_SUBJECT_CERTIFICATE'))->replyTo(env('MAIL_TO'))->with([
                'data' => $this->value,
            ])->attachData(base64_decode($this->value['pdf']), $this->value['nameDocument'], [
                'mime' => 'application/pdf',
            ]);
        }

        if (isset($this->value['is_service_email']) && $this->value['is_service_email']) {
            return $this->view('email.email_settlement')->subject(env('DOCUMENT_SUBJECT_SALARY'))->replyTo(env('MAIL_TO'))->with([
                'data' => $this->value,
            ])->attachData(base64_decode($this->value['pdf']), $this->value['nameDocument'], [
                'mime' => 'application/pdf',
            ]);
        }

        if (isset($this->value['is_constribution_email']) && $this->value['is_constribution_email']) {
            return $this->view('email.email_constributional')->subject($this->value['title'])->replyTo(env('MAIL_TO'))->with([
                'data' => $this->value,
            ]);
        }
       
        if (isset($this->value['is_request_delete'])) {
            return $this->view('email.email_request')->subject($this->value['title'])->replyTo(env('MAIL_TO'))->with([
                'data' => $this->value,
            ]);
        }

        return $this->view('email.recover_password')->subject(env('DOCUMENT_SUBJECT_CONTRASENA'))->replyTo(env('MAIL_TO'))->with([
            'data' => $this->value,
        ]);
    }
}
