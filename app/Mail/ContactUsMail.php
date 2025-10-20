<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactUsMail extends Mailable
{
    public $name;
    public $email;
    public $phone;
    public $country;
    public $userMessage;

    public function __construct($name, $email, $phone, $country, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->country = $country;
        $this->userMessage = $message; // ← وحدث هنا
    }

    public function build(): ContactUsMail
    {
        return $this->subject('New Contact Us Message')
            ->view('emails.contactus')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'country' => $this->country,
                'userMessage' => $this->userMessage, // ← وحدث هنا أيضًا
            ]);
    }
}
