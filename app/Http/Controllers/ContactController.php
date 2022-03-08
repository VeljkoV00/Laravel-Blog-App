<?php

namespace App\Http\Controllers;

use App\Mail\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(){

        return view('contact');
    }

    public function store(Request $request){

       $data =  $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);

        Mail::to('veljkovelimirovic7@gmail.com')->send(new Contact($data));

        return redirect()->route('contact.index')->with('success', 'We will be in touch soon');

  
    }
}
