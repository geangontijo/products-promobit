<?php

namespace App\Database;

use ClanCats\Hydrahon\Builder;
use PDO;

abstract class PDOFactory
{
    public static function create(): PDO
    {
        return new PDO('mysql:host=127.0.0.1;dbname=promobit_challenge;charset=utf8', 'root', '123456');
    }
}
