<?php

namespace App\Config;

use Exception;
use PDO;

class DBConnect extends PDO
{

    public function __construct(
        $dsn = 'mysql:host=host;dbname=dbname;charset=utf8',
        $username = 'root',
        $passwd = 'password',
        $options = NULL)
    {
        try {
            parent::__construct($dsn, $username, $passwd, $options);
        } catch (Exception $e) {
            header('Location: /');
        }
    }

    public function getEmailLogin()
    {
        return [
            'username' => 'address@mail.com',
            'password' => 'azerty'
        ];
    }

}
