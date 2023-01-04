<?php
class Database {

    public static function getConnection()
    {
        $auth_file = file_get_contents('auth.txt');
        $lines = explode("\n", $auth_file);
        $host = substr($lines[0], 5);
        $username = substr($lines[1], 9);
        $password = substr($lines[2], 9);
        $database = substr($lines[3], 7);

        return new mysqli($host, $username, $password, $database);
    }
}
