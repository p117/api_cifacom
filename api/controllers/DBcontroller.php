<?php

/**
 * Class DBcontroller : interface SGBD
 *
 */
define("DB_HOST", "localhost");
define("BASE", "api_cifacom");
define("PORT", "3306");
define("DB_USER", "root");
define("DB_PASS", "");

class DBcontroller {

    public static $Base;

    //tente la connexion sur le SGBD, en utilisant des constantes définies
    private function __construct() {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );

        self::$Base = new PDO("mysql:host=" . DB_HOST . ";dbname=" . BASE . ";port=" . PORT, DB_USER, DB_PASS, $options);
        self::$Base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //retourne l'instance unique
    public static function get_instance() {
        if (self::$Base == NULL)
            new DBcontroller;
        return self::$Base;
    }

}
