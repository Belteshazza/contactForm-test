<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactSubmissionNotification;

class ContactsController extends Controller
{

    public function store(Request $request){
   
        // Validate the form data
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'attachment' => 'required|file|mimes:png,svg,csv|max:1024',
        'message' => 'required',
    ]);

    // Check if the submission is a duplicate
    $existingSubmission = Contact::where('email', $validatedData['email'])
        ->where('message', $validatedData['message'])
        ->first();
    if ($existingSubmission) {
        return response()->json([
            'message' => 'This submission has already been received',
        ], 409);
    }

    // Store the submission in the database
    $submission = new Contact();
    $submission->name = $validatedData['name'];
    $submission->email = $validatedData['email'];
    $submission->attachment = $request->file('attachment')->store('attachments');
    $submission->message = $validatedData['message'];
    $submission->save();

    // Send email notification
    Mail::to(env('EMAIL_NOTIFICATION_ADDRESS'))->send(new ContactSubmissionNotification($submission));


    return response()->json([
        'message' => 'Your submission has been received',
    ], 201);
    
  }

}