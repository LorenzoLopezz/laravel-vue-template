<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;

class MailController extends Controller
{
  public function index(Request $request)
  {
    $mailData = [
      'title' => 'Mail from tesmail.com',
      'body' => 'This is for testing email using smtp.'
    ];

    Mail::to($request->email)->send(new SendMail($mailData));

    return "Email is sent successfully.";
  }
}
