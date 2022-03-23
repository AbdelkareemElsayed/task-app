<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rules\postCode;

use App\User;

use App\Mail\welcomeMail;

use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    //

    # Return Register Blade File ....
     public function register(){

        return view('User.Register',['title' => "Register Account"]);

    }


    # Do Register Operation ....
    public function doRegister(Request $request){

        $data = $this->validate($request,[

            "name"      => "required|regex:/^[a-zA-z\s]*$/",
            "email"     => "required|email|unique:users",
            "password"  => "required|min:6",
            "postcode"  => ["required", new postCode]
        ]);


        # Insert Data Using Model ....
        $data['password'] = bcrypt($data['password']);

        $data = User::create($data);

        if($data){
            $Message = "Raw Inserted";

            $details = [
                'title' => 'Welcome Email',
                'body' => 'Welcome To Our App ... '
            ];
            # Send Mail To Registered User
            Mail::to($request->email)->send(new welcomeMail($details));


        }else{
            $Message = "Error Try Again";
        }

        session()->flash('Message',$Message);

        return back();


    }



}
