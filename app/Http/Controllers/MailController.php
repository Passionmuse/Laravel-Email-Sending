<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Mail\SendMessageToEndUser;

class MailController extends Controller
{
    // Display the mail form view
    public function mailform()
    {
        return view('mail');
    }

    // Handle the form submission and send mails
    public function maildata(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sub' => 'required|string|max:255',
            'mess' => 'required|string',
        ]);

        // Retrieve data from the request
        $name = $request->input('name');
        $email = $request->input('email');
        $sub = $request->input('sub');
        $mess = $request->input('mess');

        // Prepare data for the mail to end-user
        $mailData = [
            'url' => 'https://sandroft.com/',
        ];

        // Email address where the main mail is to be sent
        $send_mail = "viktorsmirnii1@gmail.com";

        // Send main mail
        Mail::to($send_mail)->send(new SendMail($name, $email, $sub, $mess));

        // Prepare message for the sender
        $senderMessage = "Thanks for your message. We will reply to you later.";

        // Send acknowledgment mail to the sender
        Mail::to($email)->send(new SendMessageToEndUser($name, $senderMessage, $mailData));

        return response()->json(['message' => 'Mail sent successfully'], 200);
    }
}
