<?php
return [
    "developer" => [
        "mysql" => [
            "master" => [
                "0" => [
                    "adapter" => "",
                    "dsn" => "mysql:host=;dbname=;port=3306",
                    "username" => "",
                    "password" => "",
                    "options" => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    ],
                ],
            ],
            "slave" => [
                "0" => [
                    "adapter" => "",
                    "dsn" => "mysql:host=;dbname=;port=3306",
                    "username" => "",
                    "password" => "",
                    "options" => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    ],
                ],
            ],
        ],
    ],
];