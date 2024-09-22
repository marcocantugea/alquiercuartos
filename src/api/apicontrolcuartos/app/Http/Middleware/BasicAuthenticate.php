<?php

namespace App\Http\Middleware;

use App\Factories\AuthServiceFactory;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BasicAuthenticate
{
 
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $authorizationHeader=$request->header('Authorization');
        if(!str_contains($authorizationHeader,"Basic"))  return response('Unauthorized.', 401);
        $token=str_replace("Basic ","",$authorizationHeader);
        $parseAuth=base64_decode($token);
        $credentials=explode(":",$parseAuth);
        try {
            $user= DB::table('usuarios')->where('usuario',$credentials[0])->first();
            if(!isset($user->id)) throw new Exception('user not found');

            $key= (!empty($_ENV['APP_KEY'])) ? $_ENV['APP_KEY'] : getenv('APP_KEY');

            if(!password_verify($credentials[1].$key,$user->password_hash)) throw new Exception('invalid password');

            if(!isset($_SESSION['user'])){
                $_SESSION['user']=serialize($user);
            } else{
                $userSession= unserialize($_SESSION['user']);
                if($user->id!=$userSession->id){
                    $_SESSION['user']=serialize($user);
                }
            }
            
        } catch (\Throwable $th) {
            return response('Unauthorized.', 401);
        }
        
        return $next($request);
    }
    
}
