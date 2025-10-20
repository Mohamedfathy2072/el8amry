<?php

namespace App\Http\Controllers\Draftech;

use App\Http\Controllers\Controller;
use App\Mail\ContactUsMail;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends BaseController
{
    public function index()
    {
        $contact = ContactUs::first();

        if (!$contact) {
            return $this->singleItemResponse(null, "No contact data found.");
        }

        return $this->singleItemResponse($contact, "Contact info fetched successfully.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotline_number'   => 'nullable|string|max:20',
            'branch_number'    => 'nullable|string|max:20',
            'whatsapp_number'  => 'nullable|string|max:20',
            'facebook_link'    => 'nullable|url',
            'instagram_link'   => 'nullable|url',
            'x_link'           => 'nullable|url',
        ]);

        ContactUs::truncate();

        $contact = ContactUs::create([
            'hotline_number'   => $request->hotline_number,
            'branch_number'    => $request->branch_number,
            'whatsapp_number'  => $request->whatsapp_number,
            'facebook_link'    => $request->facebook_link,
            'instagram_link'   => $request->instagram_link,
            'x_link'           => $request->x_link,
        ]);

        return response()->json([
            'message' => 'تم إضافة بيانات الاتصال بنجاح',
            'data' => $contact
        ], 201);
    }
    public function sendMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);


        // إرسال البريد الإلكتروني
        Mail::to('mohamed.fathy30112000@gmail.com')->send($contactUsMail = new ContactUsMail(
            $request->name,
            $request->email,
            $request->phone,
            $request->country,
            $request->message
        ));

        return response()->json(['message' => 'Your message has been sent successfully!'], 200);
    }



}
