<?php

namespace App\Http\Middleware;

use App\Core\ClientDbSwitcher;
use App\User;
use App\UserDatabase;
use Closure;

class SelectDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->guard('api')->user()) {
            /**
             * @var $client Client
             */
            $user = User::find(auth()->guard('api')->user()->id);
            $connection = UserDatabase::find($user->connection);

            try {
                ClientDbSwitcher::switchToDb($connection);
            }
            catch ( \Exception $e) {
                return apiResponse(
                    API_ERROR,
                    null,
                    'Error al seleccionar DB del cliente',
                    500);
            }
        }
        return $next($request);
    }
}
