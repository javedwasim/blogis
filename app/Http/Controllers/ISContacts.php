<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Mail;
use App\Setting;
use Request;
use Redirect;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Infusionsoft\Infusionsoft;

class ISContacts extends Controller
{
    public function LoadContacts(){

        $infusionsoft = new Infusionsoft(array(
            'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URL'),
        ));

        $setting = Setting::orderBy('id', 'desc')->first();
        $infusionsoft->setToken(unserialize($setting->infusion_token));

        try {


            $isCustomFields = array();
            $page = 0;

            while (true) {

                $queryData = array('Id' => '%');
                $selectedFields = array(
                    'Id',
                    'FirstName',
                    'LastName',
                    'Email',

                );
                $orderBy = 'Id';
                $contacts = $infusionsoft->data()->query('Contact', 1000, 0, $queryData, $selectedFields, $orderBy, true);

                $isCustomFields = array_merge($isCustomFields, $contacts);
                if (count($contacts) < 1000) {
                    break;
                }
                $page++;
            }

            // echo "<pre>";  print_r($isCustomFields);

        } catch (\Infusionsoft\TokenExpiredException $e) {

            // If the request fails due to an expired access token, we can refresh the token and then do the request again.
            $infusionsoft->refreshAccessToken();
            $setting->fill(array('infusion_token' => serialize($infusionsoft->getToken())));
            $setting->save();

            $isCustomFields = array();
            $page = 0;

            while (true) {

                $queryData = array('Id' => '%');
                $selectedFields = array(
                    'Id',
                    'FirstName',
                    'LastName',
                    'Email',

                );
                $orderBy = 'Id';
                $contacts = $infusionsoft->data()->query('Contact', 1000, 0, $queryData, $selectedFields, $orderBy, true);

                $isCustomFields = array_merge($isCustomFields, $contacts);
                if (count($contacts) < 1000) {
                    break;
                }
                $page++;
            }

        }

        //dd($results);
        return view('iscontacts',compact('contacts'));

    }

    public function delete(){

        $request = Request::all();
        $contactid = $request['contactid'];

        if(!empty($contactid)){

            $infusionsoft = new Infusionsoft(array(
                'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
                'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
                'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URL'),
            ));
            $setting = Setting::orderBy('id', 'desc')->first();
            $infusionsoft->setToken(unserialize($setting->infusion_token));



            try{

                $result = $infusionsoft->data()->delete('Contact', $contactid);

                if($result){
                    // sending back with message
                   echo "success";
                }
            }catch (\Infusionsoft\TokenExpiredException $e) {

                // If the request fails due to an expired access token, we can refresh the token and then do the request again.
                $infusionsoft->refreshAccessToken();
                $setting->fill(array('infusion_token' => serialize($infusionsoft->getToken())));
                $setting->save();

                $result = $infusionsoft->data()->delete('Contact', $contactid);
                if($result){
                    echo "success";
                }

            }
        }

    }
}
