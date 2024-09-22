<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class AuthenticateController extends Controller
{
    public function authenticateUser(Request $request){

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->usuario) || !isset($jsonParsed->password)){
            return new Response($this->stdResponse(false,true,'usuario o password nodos no encontrados'),404);
        }

        $user= DB::table('usuarios')
                ->join('roles','roles.id','=','usuarios.roleId')
                ->select('usuarios.publicId','usuarios.password_hash','usuarios.id','roles.rol')
                ->where('usuario',$jsonParsed->usuario)
                ->whereNull('usuarios.fecha_eliminado')
                ->where('usuarios.activo','=',1)
                ->first();
        if(!isset($user->id)) return new Response($this->stdResponse(false,true,'usuario no encontrado'),404);

        $key= (!empty($_ENV['APP_KEY'])) ? $_ENV['APP_KEY'] : "";
        if(!password_verify($jsonParsed->password.$key,$user->password_hash)) return new Response($this->stdResponse(false,true,'password invalido'),400);

        $token=['token'=>base64_encode($jsonParsed->usuario.":".$jsonParsed->password),'usuarioId'=>$user->publicId,'rol'=>$user->rol];

        return new Response($this->stdResponse(data:$token));
    }
}
