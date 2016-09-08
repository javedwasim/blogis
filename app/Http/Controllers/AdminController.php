<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Mail;
use App\Setting;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Infusionsoft\Infusionsoft;

class AdminController extends Controller
{
    Public function settings(){

        $user = Auth::user();
        $setting = Setting::where(['users_id'=>$user->id] )->orderBy('id', 'desc')->first() ;
        $infusionSoft = new Infusionsoft(array(
            'clientId'     => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri'  => getenv('INFUSIONSOFT_REDIRECT_URL'),
        ));

        $connected = false;
        $infusion_auth_url = $infusionSoft->getAuthorizationUrl();
        if( ! $setting ) {
            $setting = new Setting( );
        } else {
            if( ! empty( $setting->infusion_token ) ) {
                $connected = true;
                //$request->session()->put('token', $setting->infusion_token ) ;
            }
        }



        return view('issetting',compact('setting','infusion_auth_url','connected'));
    }

    public function InfusionsoftCallBack( Request $request )
    {
        $user = Auth::user();
        $infusionSoft = new Infusionsoft(array(
            'clientId'     => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri'  => getenv('INFUSIONSOFT_REDIRECT_URL'),
        ));
        // If we are returning from Infusionsoft we need to exchange the code for an access token
        if ( $request->has('code') and !$infusionSoft->getToken()) {
            $infusionSoft->requestAccessToken( $request->get('code') );
        }
        if ($infusionSoft->getToken()) {
            // Save the serialized token to the current session for subsequent requests
            // NOTE: this can be saved in your database - make sure to serialize the entire token for easy future access
            $setting = Setting::where(['users_id'=>$user->id] )->orderBy('id', 'desc')->first() ;
            if( ! $setting ) {
                $setting = new Setting( );
            }
            $setting->fill( array('users_id'=>$user->id) );
            $setting->fill( array('infusion_token'=>serialize($infusionSoft->getToken()) ) );
            $setting->save();
            //$returnFields = array('ContactGroup','ContactId','GroupId');
            //$result = $this->ssDsQuery('ContactGroupAssign',array('Contact.Id' => '%%'),$returnFields );

            $request->session()->flash('alert-success', 'Data has been saved successfully.');
        } else {
            //echo '<a href="' . $infusionSoft->getAuthorizationUrl() . '">Click here to authorize</a>';
            //return;
        }
        return redirect()->to('setting');
    }


}
