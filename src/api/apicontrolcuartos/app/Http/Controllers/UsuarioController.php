<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class UsuarioController extends Controller
{
    public function addUsuario(Request $request){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->usuario) && !isset($jsonParsed->password) && !isset($jsonParsed->roleId)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        $role= DB::table('roles')->where('publicId',$jsonParsed->roleId)->first();

        if(!isset($role->id)) return new Response($this->stdResponse(false,true,'role invalido'),400);

        $key= (!empty($_ENV['APP_KEY'])) ? $_ENV['APP_KEY'] : "";
        $options= [
            'cost'=>8
        ];

        $password=password_hash($jsonParsed->password.$key,PASSWORD_DEFAULT,$options);
        DB::table('usuarios')->insert([
             ['publicId'=>uniqid(),'usuario'=>$jsonParsed->usuario,'password_hash'=>$password,'created_at'=>new DateTime(),'roleId'=>$role->id]
        ]);

        return new Response($this->stdResponse());

    }

    public function deactivateUsuario($publicId){
        
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,'id invalido'),400);

        DB::table('usuarios')->where('publicId',$publicId)->update([
            'updated_at'=>new DateTime(),
            'activo'=>false
        ]);

        return new Response($this->stdResponse());
    }

    public function activateUsuario($publicId){
        
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,'id invalido'),400);

        DB::table('usuarios')->where('publicId',$publicId)->update([
            'updated_at'=>new DateTime(),
            'activo'=>true
        ]);

        return new Response($this->stdResponse());
    }

    public function deleteUsuario($publicId){
        
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,'id invalido'),400);

        DB::table('usuarios')->where('publicId',$publicId)->update([
            'fecha_eliminado'=>new DateTime(),
            'activo'=>false
        ]);

        return new Response($this->stdResponse());
    }

    public function getUsuario($publicId){
        if(empty($publicId)) return new Response($this->stdResponse(false,true,'id invalido'),400);

        $usuario= DB::table('usuarios')
                    ->join('roles','roles.id','=','usuarios.roleId')
                    ->select(
                        'usuarios.publicId',
                        'usuarios.usuario',
                        'usuarios.activo',
                        'usuarios.created_at',
                        'usuarios.updated_at',
                        'roles.publicId as roleId',
                        'roles.rol',
                        'roles.created_at as rolCreatedAt',
                        'roles.updated_at as rolUpdatedAt'
                    )
                    ->where('usuarios.publicId',$publicId)
                    ->whereNull('usuarios.fecha_eliminado')
                    ->first();

        if(!isset($usuario->id)) new Response($this->stdResponse(false,true,"item not found"),404);

        $obj=[
            'publicId'=>$usuario->publicId,
            'usuario'=>$usuario->usuario,
            'activo'=>$usuario->activo,
            'created_at'=>$usuario->created_at,
            'updated_at'=>$usuario->updated_at,
            'role'=>[
                'publicId'=>$usuario->roleId,
                'role'=>$usuario->rol,
                'created_at'=>$usuario->rolCreatedAt,
                'updated_at'=>$usuario->rolUpdatedAt
            ]
        ];

        return new Response($this->stdResponse(data:$obj));

    }

    public function getUsuarios(){
        $usuarios= DB::table('usuarios')
        ->join('roles','roles.id','=','usuarios.roleId')
        ->select(
            'usuarios.publicId',
            'usuarios.usuario',
            'usuarios.activo',
            'usuarios.created_at',
            'usuarios.updated_at',
            'roles.publicId as roleId',
            'roles.rol',
            'roles.created_at as rolCreatedAt',
            'roles.updated_at as rolUpdatedAt'
        )
        ->whereNull('usuarios.fecha_eliminado')
        ->get();

        $objs=[];

        foreach($usuarios as $usuario){
            $obj=[
                'publicId'=>$usuario->publicId,
                'usuario'=>$usuario->usuario,
                'activo'=>$usuario->activo,
                'created_at'=>$usuario->created_at,
                'updated_at'=>$usuario->updated_at,
                'role'=>[
                    'publicId'=>$usuario->roleId,
                    'role'=>$usuario->rol,
                    'created_at'=>$usuario->rolCreatedAt,
                    'updated_at'=>$usuario->rolUpdatedAt
                ]
            ];

            array_push($objs,$obj);
        }
        

        return new Response($this->stdResponse(data:$objs));
    }

    public function getRoles() {
        $roles= DB::table('roles')->whereNull('fecha_eliminado')->select(['publicId','rol'])->get();
        return new Response($this->stdResponse(data:$roles));
    }

    public function updatePassword(Request $request,$publicId){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->password)){
            return new Response($this->stdResponse(false,true,'invalid request'),400);
        }

        try {
            $key= (!empty($_ENV['APP_KEY'])) ? $_ENV['APP_KEY'] : getenv('APP_KEY');

            $options= [
                'cost'=>8
            ];

            DB::table('usuarios')->where('publicId',$publicId)->whereNull('fecha_eliminado')->update([
                'password_hash'=>password_hash($jsonParsed->password.$key,PASSWORD_DEFAULT,$options)
            ]);
        } catch (\Throwable $th) {
            return new Response($this->stdResponse(false,true,'Exception found'),500);
        }

        return new Response($this->stdResponse());
        
    }

}
