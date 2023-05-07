<?php

namespace App\Http\Controllers\Auth\Api;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EmailVerificationController
{
    public function notice()
    {
        return Response::json(
            data: [
                'message' => 'you must verify your email address to continue'
            ]
        );
    }

    public function verify(EmailVerificationRequest $emailVerificationRequest)
    {
        $emailVerificationRequest->fulfill();

        return Response::json(
          data: [
              'message' => 'your email address has been verified'
            ]
        );
    }

    public function resendEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return Response::json(
            data: [
                'message' => 'Verification link sent!'
            ]
        );
    }
}
