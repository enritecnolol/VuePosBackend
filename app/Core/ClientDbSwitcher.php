<?php
namespace App\Core;
use App\User;
use App\UserDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
class ClientDbSwitcher
{
    public static function switchToClientDb ($user_id) {
        $user = User::findOrFail($user_id);
        $connection = UserDatabase::find($user->connection);
        self::switchToDb($connection);
    }
    public static function switchToDb($connection_data) {
        self::replaceDbConfig($connection_data);
        DB::purge('client');
    }
    public static function replaceDbConfig($connection_data) {
        Config::set('database.connections.client.database', $connection_data['name']);
        Config::set('database.connections.client.host', $connection_data['host']);
        Config::set('database.connections.client.port', $connection_data['port']);
        Config::set('database.connections.client.username', $connection_data['username']);
        Config::set('database.connections.client.password', $connection_data['password']);
    }
}
