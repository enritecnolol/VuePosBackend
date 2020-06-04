<?php

namespace App\Http\Controllers;

use App\Core\ClientDbSwitcher;
use App\User;
use App\UserDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        $credentials = $request->only('email', 'password');

        if($token = auth()->guard('api')->attempt($credentials)){

            $currentUserId = auth()->guard('api')->user()->id;
            $currentUserName = auth()->guard('api')->user()->name;
            try{
                ClientDbSwitcher::switchToClientDb($currentUserId);
            }catch (\Exception $e){
                return apiResponse(
                    API_ERROR,
                    null,
                    'Error al seleccionar BD',
                    500
                );
            }

            return apiResponse(
                API_SUCCESS,
                ['token'=>$token, 'user' => $currentUserName],
                "Inicio de session con exito",
                200);
        }else{
            return apiResponse(
                API_FAIL,
                ['error_code'=>'badcredentials'],
                "Error en las credenciales");
        }

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:main.users',
            'password' => 'required|string|min:6|confirmed',
            'name_database' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password_database' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $name_database = str_replace(" ", "_", $request['name_database']);

        if(UserDatabase::all()->where('name', $name_database)->first()){
            $date =  time();
            $name_database = "sofia_".$name_database."_".$date;
        }

        $connection = UserDatabase::create([
            'name' => $name_database,
            'host' => $request['host'],
            'port' => $request['port'],
            'username' => $request['username'],
            'password' => $request['password_database'],
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->connection = $connection->id;
        $user->save();

        Artisan::call('db:create', ['name'=> $name_database]);
        ClientDbSwitcher::switchToClientDb($user->id);
        $this->databaseMigration();

        $token = JWTAuth::fromUser($user);

        return apiSuccess(null, "El client se registro con exito");
    }

    public function databaseMigration(){
        Artisan::call('migrate', [
            '--database' => 'client',
            '--force' => true,
            '--path' => '/database/migrations/client'
        ]);
    }

    public function logout(){
        auth()->logout();
        return apiResponse(
            API_SUCCESS,
            null,
            'Deslogueado satifactorio'
        );
    }

    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }
}
