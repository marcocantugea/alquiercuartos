<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //

    protected function stdResponse($success=true,$error=false,$message="",$data=null){
        return ["success"=>$success,"error"=>$error,"message"=>$message,"data"=>$data];
    }

    protected function userHasRoles(array $roles) : bool {
        $validation=false;
        if(!isset($_SESSION['user'])) return $validation;

        $userSession= unserialize($_SESSION['user']);
        if(empty($userSession)) return false;
        $rolesUser=DB::table('roles')->select('rol')->where('id',$userSession->roleId)->get();
        
        $items=[];
        foreach ($rolesUser as $key => $value) {
            array_push($items,$value->rol);
        }

        return in_array($items[0],$roles);
    }


}
