<?php

/*namespace Config;

use mysqli;

class Database extends mysqli
{

    public function __construct()
    {
        try {

            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            parent::__construct(
                hostname: "localhost",
                username: "root",
                password: "",
                database: "investment",
            );

            parent::set_charset("utf8mb4");
        } catch (\mysqli_sql_exception $e) {

            throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
        }
    }
}*/

$link = new mysqli("localhost", "qfsledg2_user", "Anthonyval1", "qfsledg2_db");
