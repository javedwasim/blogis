<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'users_id',
        'infusion_app_id',
        'infusion_app_key',
        'ajile_domain',
        'ajile_user_email',
        'ajile_app_key',
        'infusion_token',
    ];
    public function get_by_user($data){
        $data=(object)$data;
        return
            $this
                ->where('users_id',$data->users_id)
                //->where('is_paid',$data->is_paid)
                ->get();
        ;
    }
}
