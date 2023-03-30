<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function basic_email() {
        dd('email');
        $data = array('name'=>"Virat Gandhi");
     
        Mail::send(['text'=>'mail'], $data, function($message) {
           $message->to('shawnw@leza.co.za', 'LegalWise')->subject
              ('Deductions have been processed and ready for download');
           $message->from('donotreply@legalwise.co.za','LegalWise');
        });
     }
}
