<?php

$config = [
    "host" => "localhost",
    "dbname" => "library_db",
    "user" => "root",
    "pass" => "",
    "options" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]
];

include "core/Database.php";
include "core/App.php";
include "core/View.php";

Database::init($config);


spl_autoload_register(function ($class) {

    if (file_exists("controllers/" . $class . ".php")) {
        include "controllers/" . $class . ".php";
    }

    if (file_exists("models/" . $class . ".php")) {
        include "models/" . $class . ".php";
    }

});

include "core/Router.php";